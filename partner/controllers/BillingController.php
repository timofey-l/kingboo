<?php

namespace partner\controllers;

use common\components\checkOrderResponse;
use common\components\paymentAvisoResponse;
use common\components\YandexHelper;
use common\models\BillingExpense;
use common\models\BillingIncome;
use common\models\BillingInvoice;
use common\models\BillingPaysYandex;
use common\models\PayMethod;
use partner\models\PartnerUser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BillingController extends Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['pay-check', 'pay-avisio', 'pay-success', 'pay-fail'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function actionPay()
    {
        $partner = PartnerUser::findOne(\Yii::$app->user->id);

        if (\Yii::$app->request->isPost) {
            $this->layout = false;
            $sum = (float)\Yii::$app->request->post('sum', false);
            $payMethod = (int)\Yii::$app->request->post('payMethod', false);

            if ($sum === false || $payMethod === false) {
                throw new BadRequestHttpException('Wrong parameters');
            }

            $payMethod = PayMethod::findOne($payMethod);

            // cоздаем счет
            $billingInvoice = new BillingInvoice();
            $billingInvoice->account_id = $partner->billing->id;
            $billingInvoice->sum = $sum;
            $billingInvoice->system = 0; // yandex kassa
            $billingInvoice->payed = false;
            $billingInvoice->currency_id = $partner->billing->currency_id;
            $billingInvoice->save();

            $yandex = \Yii::$app->params['yandex'];

            $formCode = YandexHelper::getForm([
                'shopId' => $yandex['shopId'],
                'scid' => $yandex['scid'],
                'sum' => $sum,
                'customerNumber' => md5($partner->id . $partner->created_at),
                'orderNumber' => $billingInvoice->id,
                'paymentType' => $payMethod->yandex_code,

                'shopSuccessUrl' => Url::to(['billing/pay-success'], 'https') ,
                'shopFailUrl' => Url::to(['billing/pay-fail'], 'https'),
            ]);

            return base64_encode($formCode);
        } else {
            $payMethods = PayMethod::find()
//                ->where(['in', 'yandex_code', ['PC', 'AC', 'MC']])
                ->all();

            return $this->render('pay', [
                'partner' => $partner,
                'payMethods' => $payMethods,
            ]);
        }
    }


    public function actionPayCheck()
    {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Wrong method');
        }

        \Yii::$app->response->format = 'yandex';
        $req = \Yii::$app->request;
        $params = \Yii::$app->params['yandex'];
        $response = [
            'type' => 'check',
            'code' => 200,
            'performedDatetime' => date(\DateTime::W3C),
            'invoiceId' => \Yii::$app->request->post('invoiceId', ''),
            'shopId' => \Yii::$app->request->post('shopId', ''),
            'message' => "",

        ];

        // проверка запроса по хэшу и shopId
        if ($req->post('shopId') != $params['shopId'] || !YandexHelper::checkMd5Common('check', $req->post(), $params)) {
            $response['code'] = 200;
            $response['message'] = "MD5 check failed";
            return $response;
        }

        // проверка invoiceId
        $invoice = BillingInvoice::findOne((int)\Yii::$app->request->post('orderNumber', 0));
        if (is_null($invoice)) {
            $response['message'] = 'Billing invoice was not found!';
            return $response;
        }

        $payYandex = new BillingPaysYandex();

        $payYandex->check_post_dump = var_export(\Yii::$app->request->post(), true);
        $payYandex->billing_invoice_id = $invoice->id;
        $payYandex->invoiceId = $response['invoiceId'];
        $payYandex->checked = true;
        $payYandex->save();

        return $response;
    }

    public function actionPaySuccess($orderNumber)
    {
        $partner = PartnerUser::findOne(\Yii::$app->user->id);
        if ($partner === null) {
            throw new ForbiddenHttpException;
        }

        $billingInvoice = BillingInvoice::findOne(['account_id' => $partner->billing->id, 'id' => (int) $orderNumber]);

        if ($billingInvoice === null) {
            throw new NotFoundHttpException;
        }

        return $this->render('success', [
            'partner' => $partner,
            'invoice' => $billingInvoice,
        ]);
    }

    public function actionPayFail()
    {
        \Yii::$app->getSession()->setFlash('danger', \Yii::t('partner_billing', 'An error has occured at making payment. Please try again.'));
        return $this->redirect(['billing/pay']);
    }

    public function actionPayAvisio()
    {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Wrong method');
        }

        \Yii::$app->response->format = 'yandex';

        /** @var BillingPaysYandex $yandexPay */
        $yandexPay = BillingPaysYandex::findOne(['invoiceId' => \Yii::$app->request->post('invoiceId', '')]);
        if ($yandexPay) {

            // сохраняем входных данных в базу
            $yandexPay->avisio_post_dump = var_export(\Yii::$app->request->post(), true);

            $yandexPay->payed = true;
            if ($yandexPay->save()) {
                $response = [
                    'type' => 'avisio',
                    'code' => 0,
                    'performedDatetime' => date(\DateTime::W3C),
                    'invoiceId' => \Yii::$app->request->post('invoiceId', ''),
                    'shopId' => \Yii::$app->request->post('shopId', ''),
                    'message' => "Оплата прошла успешно",
                ];
            } else {
                $response = [
                    'type' => 'avisio',
                    'code' => 200,
                    'performedDatetime' => date(\DateTime::W3C),
                    'invoiceId' => \Yii::$app->request->post('invoiceId', ''),
                    'shopId' => \Yii::$app->request->post('shopId', ''),
                    'message' => "Ошибка при сохнанении оплаты",
                ];
            }
        } else {
            $response = [
                'type' => 'avisio',
                'code' => 200,
                'performedDatetime' => date(\DateTime::W3C),
                'invoiceId' => \Yii::$app->request->post('invoiceId', ''),
                'shopId' => \Yii::$app->request->post('shopId', ''),
                'message' => "Счет " . \Yii::$app->request->post('invoiceId', '') . ' не найден.',
            ];
        }

        return $response;
    }

    public function actionTransactions()
    {
        $query = new Query();

        $incomes = new Query();
        $incomes->select(['id', 'date', 'sum', 'currency_id', ' CONCAT(1) as type', 'CONCAT(\'\') as comment'])
            ->from(BillingIncome::tableName());


        $expenses = new Query();
        $expenses->select(['id', 'date', 'sum', 'currency_id', ' CONCAT(2) as type', 'comment'])
            ->from(BillingExpense::tableName());

        $expensesQuery = clone $expenses;

        $expenses->union($incomes, true)->orderBy(['date' => SORT_ASC]);

        $query->select('*')->from(['u' => $expenses])->orderBy(['date' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('transactions', [
            'dataProvider' => $dataProvider,
            'expensesQuery' => $expensesQuery,
            'incomesQuery' => $incomes,
        ]);


    }
}
