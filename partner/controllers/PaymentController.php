<?php

namespace partner\controllers;

use Yii;
use common\components\YandexHelper;
use common\models\Order;
use common\models\Pay;
use common\models\PayLog;
use partner\models\PartnerUser;
use yii\filters\VerbFilter;
use common\models\Lang;

class PaymentController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'avisio' => ['post'],
                    'check' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Уведомление Контрагента о принятом переводе. Этот запрос обозначает факт успешного перевода денежных средств
     * плательщика в адрес Контрагента и обязанность Контрагента выдать товар плательщику.
     * Обратите внимание: на этом шаге Контрагент не может отказаться от приема перевода.
     *
     * @return string
     */
    public function actionAvisio()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');
        $req = \Yii::$app->request;

        /** @var PayLog $payLog */
        $payLog = new PayLog();
        $payLog->add('yandex-aviso');

        // проверяем запрос
        $partner = PartnerUser::findOne(['shopId' => $req->post('shopId')]);
        if ($partner == null || !YandexHelper::checkMd5('paymentAviso', $req->post(), $partner)) {
            $payLog->response(1, 'md5 check failed');
            return $this->renderPartial('avisio', [
                'code' => 1,
                'post' => $req->post(),
                'message' => '',
            ]);
        }

        // ищем уже созданную запись об оплате при запросе "check"
        /** @var Pay $pay */
        $pay = Pay::findOne([
            'checked' => 1,
            'payed' => 0,
            'order_number' => $req->post('orderNumber'),
            'invoiceId' => $req->post('invoiceId'),
            'shopId' => $req->post('shopId'),
        ]);

        if ($pay === null) {
            $payLog->response(200, 'Payment was not found');
            return $this->renderPartial('avisio', [
                'post' => $req->post(),
                'code' => 200,
                'message' => 'Internal server error',
                'techMessage' => 'checkOrder action was not found',
            ]);
        }

        /** @var Order $order */
        $order = Order::findOne(['number' => $req->post('orderNumber')]);
        
        // Проверяем не оплачен ли уже заказ
        if ($order->status == Order::OS_PAYED && $order->payment_url == '') {
            $payLog->response(0, 'Repeat confirmation');
            return $this->renderPartial('avisio', [
                'post' => $req->post(),
                'code' => 0,
                'message' => 'Successful payment',
            ]);
        }

        $pay->paymentDatetime = $req->post('paymentDatetime');
        $pay->postParams = serialize($req->post());
        $pay->payed = 1;
        if ($pay->save()) {
            $order->status = Order::OS_PAYED;
            $order->payment_url = '';
            if ($order->save()) {
                $payLog->response(0, 'Successful payment');
                return $this->renderPartial('avisio', [
                    'post' => $req->post(),
                    'code' => 0,
                    'message' => 'Successful payment',
                ]);
            }
        }

        $payLog->response(200, 'DB save error');
        return $this->renderPartial('avisio', [
            'post' => $req->post(),
            'code' => 200,
            'message' => 'DB save error',
        ]);
    }

    /**
     * Вызов checkOrder от api Яндекс Кассы
     * Запрос проверки корректности параметров заказа. Этот шаг позволяет исключить ошибки, которые могли возникнуть при
     * прохождении платежной формы через браузер плательщика.
     * В случае успешного ответа Контрагента Оператор предлагает плательщику оплатить заказ и при успехе отправляет
     * Контрагенту «Уведомление о переводе».
     *
     * @return string
     */
    public function actionCheck()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = \Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');

        $req = \Yii::$app->request;

        /** @var PayLog $payLog */
        $payLog = new PayLog();
        $payLog->add('yandex-check');

        $pay = new Pay();
        $pay->load($req->post(), '');
        $pay->order_number = $req->post('orderNumber');

        /** @var Order $order */
        $order = Order::findOne(['number' => $pay->order_number]);
        // проверяем есть ли заказ
        if ($order === null) {
            $payLog->response(100, "Wrong order number {$pay->order_number}");
            return $this->renderPartial('check', [
                'code' => 100,
                'pay' => $pay,
                'order' => $order,
                'post' => $req->post(),
                'message' => \Yii::t('payment', 'Wrong order number'),
            ]);
        }

        $pay->paymentType = YandexHelper::getIdByCode($order->yandex_payment_type);

        $pay->orderSumAmount = $req->post('orderSumAmount');
        $pay->paymentDatetime = '';

        $pay->checked = 1;
        $pay->payed = 0;

        $pay->postParams = serialize($req->post());

        $partner = $order->hotel->partner;
        if (!YandexHelper::checkMd5('checkOrder', \Yii::$app->request->post(), $partner)) {
            $payLog->response(1, 'md5 check failed');
            return $this->renderPartial('check', [
                'code' => 1,
                'pay' => $pay,
                'order' => $order,
                'post' => $req->post(),
                'message' => '',
            ]);
        }

        // проверяем статус заказа
        if ($order->status == Order::OS_PAYED) {
            $payLog->response(100, 'Order is payed already');
            return $this->renderPartial('check', [
                'code' => 100,
                'pay' => $pay,
                'order' => $order,
                'post' => $req->post(),
                'message' => \Yii::t('payment', 'This order already payed', Lang::findOne(['url' => $order->lang])->local),
            ]);
        } else if ($order->status != Order::OS_WAITING_PAY) {
            $payLog->response(100, 'Wrong order status');
            return $this->renderPartial('check', [
                'code' => 100,
                'pay' => $pay,
                'order' => $order,
                'post' => $req->post(),
                'message' => \Yii::t('payment', 'Wrong order status', Lang::findOne(['url' => $order->lang])->local),
            ]);
        }

        // проверяем сумму заказа
        if ($order->payment_system_sum != $pay->orderSumAmount) {
            $payLog->response(100, 'Wrong order sum');
            return $this->renderPartial('check', [
                'code' => 100,
                'pay' => $pay,
                'order' => $order,
                'post' => $req->post(),
                'message' => \Yii::t('payment', 'Wrong order sum', Lang::findOne(['url' => $order->lang])->local),
            ]);
        }

        // все хорошо, пробуем создать запись
        if ($pay->validate() && $pay->save(false)) {
            $payLog->response(0, 'Successful payment');
            return $this->renderPartial('check', [
                'code' => 0,
                'pay' => $pay,
                'order' => $order,
                'post' => $req->post(),
                'message' => '',
            ]);
        } else {
            // посылаем письмо с ошибкой
            \Yii::$app->mailer->compose([
                'html' => 'errorAdminEmail-html',
                'text' => 'errorAdminEmail-text',
            ], [
                'message' => var_export($pay, true),
            ])->setFrom(\Yii::$app->params['email.from'])
                ->setTo(\Yii::$app->params['adminEmail'])
                ->setSubject('Error on booking.local [paymentCheck]')
                ->send();
            $payLog->response(100, 'DB save error');
            return $this->renderPartial('check', [
                'code' => 100,
                'pay' => $pay,
                'order' => $order,
                'post' => $req->post(),
                'message' => \Yii::t('payment', 'Internal server error', Lang::findOne(['url' => $order->lang])->local),
            ]);
        }
    }

}
