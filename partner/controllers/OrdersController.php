<?php

namespace partner\controllers;

use common\models\Order;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class OrdersController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function actionIndex($page = 0, $pageSize = 3)
    {
        $query = $this->getOrdersActiveQuery();
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $provider,
        ]);
    }

    /**
     * Получение списка заказов
     * @return array|\yii\db\ActiveQuery
     */
    public function getOrdersActiveQuery()
    {
        return Order::find()
            ->joinWith('hotel.partner')
            ->where(['partner_user.id' => \Yii::$app->user->id]);
            //->orderBy(['created_at' => SORT_DESC]);
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
    public function actionSetAll($type = '')
    {
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

    public function actionPayed($id)
    {
        /** @var Order $order */
        $order = Order::findOne($id);
        if ($order->hotel->partner->id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        if (!$order) {
            throw new BadRequestHttpException('Wrong order_id');
        }
        $order->status = Order::OS_PAYED;
        $order->save(false);
        if (\Yii::$app->request->referrer) {
            return $this->redirect(\Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionCancel($id)
    {
        /** @var Order $order */
        $order = Order::findOne($id);
        if ($order->hotel->partner->id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        if (!$order) {
            throw new BadRequestHttpException('Wrong order_id');
        }
        if ($order->status == Order::OS_CANCELED) {
            throw new BadRequestHttpException('Order already cancelled.');
        }
        if ((new \DateTime())->diff(new \DateTime($order->dateFrom))->invert) {
            throw new BadRequestHttpException('Order is outdated');
        }

        $order->status = Order::OS_CANCELED;
        $order->save(false);
        if (\Yii::$app->request->referrer) {
            return $this->redirect(\Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionViewed($id)
    {
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
