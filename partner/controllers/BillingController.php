<?php

namespace partner\controllers;

use common\components\checkOrderResponse;
use common\components\paymentAvisoResponse;
use common\components\YandexHelper;
use common\models\BillingInvoice;
use common\models\BillingPaysYandex;
use common\models\PayMethod;
use partner\models\PartnerUser;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class BillingController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['pay-check', 'pay-avisio'],
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
            $sum = (float) \Yii::$app->request->post('sum', false);
            $payMethod = (int) \Yii::$app->request->post('payMethod', false);

            if ($sum === false || $payMethod === false) {
                throw new BadRequestHttpException('Wrong parameters');
            }

            // cоздаем счет
            $billingInvoice = new BillingInvoice();
            $billingInvoice->account_id = $partner->billing->id;
            $billingInvoice->sum = $sum;
            $billingInvoice->system = 0; // yandex kassa
            $billingInvoice->payed = false;
            $billingInvoice->currency = $partner->billing->currency_id;
            $billingInvoice->save();

            $yandex = \Yii::$app->params['yandex'];

            $formCode = YandexHelper::getForm([
                'shopId' => $yandex['shopId'],
                'scid' => $yandex['scid'],
                'sum' => $sum,
                'customerNumber' => md5($partner->id.$partner->created_at),
                'orderNumber' => $billingInvoice->id,
            ]);

            return base64_encode($formCode);
        } else {
            $payMethods = PayMethod::find()->where(['in', 'yandex_code', ['PC', 'AC', 'MC']])->all();

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
            'type' => 'avisio',
            'code' => 200,
            'performedDatetime' => date(\DateTime::W3C),
            'invoiceId' => \Yii::$app->request->post('invoiceId', ''),
            'shopId' => \Yii::$app->request->post('shopId', ''),
            'message' => "",

        ];

        // проверка запроса по хэшу и shopId
        if ($req->post('shopId') != $params['shopId'] || !YandexHelper::checkMd5Common('check', $req->post(), $params)) {
            $response['code'] = 200;
            return $response;
        }

        // проверка invoiceId
        $invoice = BillingInvoice::findOne((int) \Yii::$app->request->post('orderNumber', 0));
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


    public function actionPayAvisio()
    {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Wrong method');
        }

        \Yii::$app->response->format = 'yandex';

        $response = [
            'type' => 'check',
            'code' => 1,
            'performedDatetime' => date(\DateTime::W3C),
            'invoiceId' => 123123,
            'shopId' => 123123,
            'message' => "sdfsdf sdf sdf",
        ];

        return $response;
    }
}