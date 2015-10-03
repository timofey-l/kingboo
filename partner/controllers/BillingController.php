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
use common\models\BillingLogs;
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
use common\models\Currency;

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
            $sum = Currency::numberFormat(\Yii::$app->request->post('sum', false));
            $payMethod = (int)\Yii::$app->request->post('payMethod', false);

            if ($sum === false || $payMethod === false) {
                throw new BadRequestHttpException('Wrong parameters');
            }

            $payMethod = PayMethod::findOne($payMethod);
       
            // cоздаем счет
            $billingInvoice = new BillingInvoice();
            $billingInvoice->account_id = $partner->billing->id;
            $billingInvoice->sum = $sum;
            $billingInvoice->system = BillingInvoice::PAY_SYSTEM_YANDEX; // yandex kassa
            $billingInvoice->created_at = date('Y-m-d H:i:s');
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
                ->where(['in', 'yandex_code', ['AC', 'GP', 'AB', 'PC', 'WM', 'PB']])
                ->orderBy('order ASC')
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

        \Yii::info('pay-check start', 'debug');
        $log = new BillingLogs();
        $log->add('yandex-check');

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
        if ($req->post('shopId') != $params['shopId'] || !YandexHelper::checkMd5Common('checkOrder', $req->post(), $params)) {
            $response['code'] = 1;
            $response['message'] = "MD5 check failed";
            $log->response($response['code'], $response['message']);
            return $response;
        }
     
        // проверка invoiceId
        $invoice = BillingInvoice::findOne((int)\Yii::$app->request->post('orderNumber', 0));
        if (is_null($invoice)) {
            $response['message'] = 'Billing invoice was not found!';
            $log->response($response['code'], $response['message']);
            return $response;
        }
     
        // проверяем не оплачен ли счет
        if ($invoice->payed) {
            $response['code'] = 100;
            $response['message'] = 'Billing invoice was payed already!';
            $log->response($response['code'], $response['message']);
            return $response;
        }
     
        $payYandex = new BillingPaysYandex();

        $payYandex->check_post_dump = var_export(\Yii::$app->request->post(), true);
        $payYandex->billing_invoice_id = $invoice->id;
        $payYandex->invoiceId = $response['invoiceId'];
        $payYandex->checked = true;
        if (!$payYandex->save()) {
            $response['code'] = 100;
            $response['message'] = 'DB save error';
            $log->response($response['code'], $response['message']);
            return $response;
        }

        $response['code'] = 0;
        $response['message'] = 'Successful check';

        $log->response($response['code'], $response['message']);
        return $response;
    }

    public function actionPayAvisio()
    {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Wrong method');
        }

        $log = new BillingLogs();
        $log->add('yandex-aviso');

        \Yii::$app->response->format = 'yandex';
        $params = \Yii::$app->params['yandex'];

        /** @var BillingPaysYandex $yandexPay */
        $yandexPay = BillingPaysYandex::findOne(['invoiceId' => \Yii::$app->request->post('invoiceId', '')]);
        if ($yandexPay) {
            
            // проверка запроса по хэшу и shopId
            if (\Yii::$app->request->post('shopId') != $params['shopId'] 
                || !YandexHelper::checkMd5Common('paymentAviso', \Yii::$app->request->post(), $params)) {
                $response['code'] = 1;
                $response['message'] = "MD5 check failed";
                $log->response($response['code'], $response['message']);
                return $response;
            }

            // если оплата уже проведена, отвечаем положительно и ничего не делаем
            if (\common\models\BillingIncome::findOne(['invoice_id' => $yandexPay->id])) {
                $response = [
                    'type' => 'avisio',
                    'code' => 0,
                    'performedDatetime' => date(\DateTime::W3C),
                    'invoiceId' => \Yii::$app->request->post('invoiceId', ''),
                    'shopId' => \Yii::$app->request->post('shopId', ''),
                    'message' => "Repeat confirm",
                ];
                $log->response($response['code'], $response['message']);
                return $response;
            }

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
                    'message' => "Successful payment",
                ];
            } else {
                $response = [
                    'type' => 'avisio',
                    'code' => 200,
                    'performedDatetime' => date(\DateTime::W3C),
                    'invoiceId' => \Yii::$app->request->post('invoiceId', ''),
                    'shopId' => \Yii::$app->request->post('shopId', ''),
                    'message' => "DB save error",
                ];
            }
        } else {
            $response = [
                'type' => 'avisio',
                'code' => 200,
                'performedDatetime' => date(\DateTime::W3C),
                'invoiceId' => \Yii::$app->request->post('invoiceId', ''),
                'shopId' => \Yii::$app->request->post('shopId', ''),
                'message' => "Invoice " . \Yii::$app->request->post('invoiceId', '') . ' is not found',
            ];
        }

        $log->response($response['code'], $response['message']);
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

    public function actionTransactions()
    {
        $partner = PartnerUser::findOne(\Yii::$app->user->id);
        $account_ids = [];
        foreach ($partner->accounts as $account) {
            $account_ids[] = $account->id;
        }

        $query = new Query();

        $incomes = new Query();
        $incomes->select(['id', 'date', 'sum', 'currency_id', ' CONCAT(1) as type', 'CONCAT(\'\') as comment'])
            ->from(BillingIncome::tableName())
            ->where(['account_id' => $account_ids]);


        $expenses = new Query();
        $expenses->select(['id', 'date', 'sum', 'currency_id', ' CONCAT(2) as type', 'comment'])
            ->from(BillingExpense::tableName())
            ->where(['account_id' => $account_ids]);

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
