<?php

namespace frontend\controllers;

use common\models\Order;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class PaymentController extends \yii\web\Controller
{
    public function actionShow($id)
    {
	    $order = Order::findOne(['payment_url' => $id]);

	    if ($order === null || $order->status !== Order::OS_WAITING_PAY) {
		    throw new NotFoundHttpException(\Yii::t('frontend', 'Page was not found!'));
	    }

        return $this->render('show', [
	        'order' => $order,
        ]);
    }

	/**
	 * Получение формы для оплаты, что бы перейти по ней с браузера пользователя
	 * Вызов идет с формы на странице оплаты.
	 * CSRF токен берется из мета тега этой страницы
	 */
	public function actionGetForm()
	{
		$req = \Yii::$app->request;
		if (!$req->validateCsrfToken($req->post('_csrf'))) {
			throw new BadRequestHttpException('CSRF validation failed');
		}

		$order_payment_url = null;
		if (preg_match('/[0-9a-zA-Z-_]{64}$/', $_SERVER['HTTP_REFERER'], $m)) {
			$order_payment_url = $m[0];
		}

		$pay_type = $req->post('pay_type', null);

		if (!$pay_type || !$order_payment_url) {
			throw new BadRequestHttpException('Wrong parameters');
		}

		/** @var Order $order */
		$order = Order::findOne(['payment_url' => $order_payment_url]);
		if (!$order) {
			throw new BadRequestHttpException('Incorrect order');
		}

		$partner = $order->hotel->partner;

		if ($order->status == Order::OS_WAITING_PAY) {
			$this->layout = false;

			return base64_encode($this->render('_pay_form', [
				'shopId'         => $partner->shopId,
				'scid'           => $partner->scid,
				'sum'            => $order->pay_sum,
				'customerNumber' => md5($order->contact_email),
				'orderNumber'    => $order->number,
				'paymentType'    => $pay_type,
				'partner'        => $partner,
				'order'          => $order,
                'shopFailURL'    => 'https://king-boo.com/payment/success',
                'shopSuccessURL' => 'https://king-boo.com/payment/fail',

			]));
		} else {
			throw new BadRequestHttpException('Wrong order status');
		}
	}

    public function actionSuccess() {
        return $this->render('success', [
            'pay' =>  null,
            'order' => null,
        ]);
    }

    public function actionFail() {
        return $this->render('fail', [
            'pay' => null,
            'order' => null,
        ]);
    }

}
