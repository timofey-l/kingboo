<?php

namespace frontend\controllers;

use common\models\Hotel;
use yii\base\ErrorException;
use yii\filters\AccessControl;

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

}
