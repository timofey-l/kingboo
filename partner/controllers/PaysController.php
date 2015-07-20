<?php

namespace partner\controllers;

use common\models\Pay;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\web\Controller;

class PaysController extends Controller
{

    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Pay::find([
                'shopId' => \Yii::$app->user->identity->shopId,
                'payed' => 1,
            ])
            ->orderBy(['paymentDatetime' => SORT_DESC]),

        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}