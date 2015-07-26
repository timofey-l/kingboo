<?php

namespace partner\controllers;

use common\models\Order;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\Response;

class OrdersController extends \yii\web\Controller
{
	/**
	 * Получение списка заказов
	 * @return array|\yii\db\ActiveQuery
	 */
	public function getOrdersActiveQuery() {
		return Order::find()
			->joinWith('hotel.partner')
			->where(['partner_user.id' => \Yii::$app->user->id])
			->orderBy(['created_at' => SORT_DESC ])
			;
	}

    public function actionIndex()
    {
	    $orders = $this->getOrdersActiveQuery()->all();

        return $this->render('index', [
	        'orders' => $orders,
        ]);
    }

    public function actionView($id)
    {
		$order = Order::findOne($id);
		if ($order->hotel->partner->id !== \Yii::$app->user->id) {
			throw new ForbiddenHttpException();
		}
	    if (!$order) {
		    throw new BadRequestHttpException('Wrong order_id');
	    }
        return $this->render('view', [
	        'order' => $order,
        ]);
    }

	/**
	 * Выполнение действия над всеми заказами
	 *
	 * @param string $type
	 * @return string
	 * @throws BadRequestHttpException
	 */
	public function actionSetAll($type = '') {
		\Yii::$app->response->format = Response::FORMAT_JSON;

		if (!\Yii::$app->request->validateCsrfToken()) {
			throw new BadRequestHttpException('CSRF validation failed');
		}

        $ids = \Yii::$app->request->post('ids', false);
        if (!$ids) {
            return false;
        }

		switch ($type) {
			// отметить все как просмотеренные
			case 'viewed':

				$orders = $this->getOrdersActiveQuery()
                    ->andWhere(['in', 'order.id', $ids])
                    ->andWhere(['viewed' => 0])
                    ->all();
				foreach ($orders as $order) {
					/* @var $order Order */
					$order->viewed = 1;
					$order->save(false);
				}
				return true;
				break;
		}
		return false;
	}

    public function actionViewed($id) {
		$order = Order::findOne($id);
		if ($order->hotel->partner->id !== \Yii::$app->user->id) {
			throw new ForbiddenHttpException();
		}
        if (!$order) {
            throw new BadRequestHttpException('Wrong order_id');
        }
        $order->viewed = 1;
        $order->save(false);
        if (\Yii::$app->request->referrer) {
            return $this->redirect(\Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

}
