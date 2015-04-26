<?php

namespace frontend\controllers;

use common\models\Hotel;
use common\models\Room;
use common\models\RoomPrices;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class HotelController extends \yii\web\Controller
{
    public function behaviours()
    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//
//                ],
//            ],
//        ];
    }

    public function actionBooking()
    {
        return $this->render('booking');
    }

    public function actionIndex($name)
    {
        $model = Hotel::findOne(['name' => $name]);

        if (is_null($model)) {
            throw new \yii\web\HttpException(404, 'The requested hotel does not exist.');
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionSearch()
    {
        // вывод в формате JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $req = \Yii::$app->request;

        // разбираем входные данные
        $dateFrom = $req->post('dateFrom', false);
        $dateTo = $req->post('dateTo', false);
        $adults = (int) $req->post('adults', false);
        $children = (int) $req->post('children', false);
        $kids = (int) $req->post('kids', false);

        if (!$dateFrom || !$dateTo || $adults === false || $children === false || $kids === false) {
            throw new BadRequestHttpException();
        }

        $prices_query = RoomPrices::find()->where(['between', 'date', $dateFrom, $dateTo]);
        $prices = $prices_query->all();

        $arr = ArrayHelper::map($prices, 'id', 'price', 'room_id');

        $rooms = Room::find()->where(['in', 'room.id', array_keys($arr)])->all();

        $result = [];

        foreach ($rooms as $room) {
            $item = $room->toArray();
            $item['images'] = [];
            foreach($room->images as $image) {
                $im = [];
                $im['image'] = $image->getUploadUrl('image');
                $im['thumb'] = $image->getThumbUploadUrl('image', 'thumb');
                $im['preview'] = $image->getThumbUploadUrl('image', 'preview');
                $item['images'][] = $im;
            }
            // считаем сумму
            $sum = 0;
            $count = 0;
            foreach ($arr[$room->id] as $s) {
                $sum += (float)($s);
                $count++;
            }
            $item['sum'] = $sum;
            $item['count'] = $count;
            $item['sum_currency'] = $prices[0]->currency->toArray();

            $result[] = $item;
        }


        return $result;

    }

}
