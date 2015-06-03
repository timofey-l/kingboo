<?php

namespace partner\controllers;

use common\models\Order;
use yii\web\BadRequestHttpException;

class OrdersController extends \yii\web\Controller
{
    public function actionIndex()
    {
	    $orders = Order::find()
		    ->joinWith('hotel.partner')
		    ->where(['partner_user.id' => \Yii::$app->user->id])
		    ->orderBy(['created_at' => SORT_DESC ])
		    ->all();

        return $this->render('index', [
	        'orders' => $orders,
        ]);
    }

    public function actionView($id)
    {
	    $order = Order::findOne($id);
	    if (!$order) {
		    throw new BadRequestHttpException('Wrong order_id');
	    }
        return $this->render('view', [
	        'order' => $order,
        ]);
    }

}
