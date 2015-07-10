<?php

namespace partner\components;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;
use yii\web\BadRequestHttpException;

class RoomsIndexAction extends IndexAction
{
    protected function prepareDataProvider()
    {
        $hotel_id = Yii::$app->request->get('hotel_id', false);
        $params = [
            'hotel.partner_id' => Yii::$app->user->id,
        ];

        if ($hotel_id !== false) {
            $params['hotel_id'] = (int) $hotel_id;
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        //var_dump($modelClass::find()->asArray()->where(['hotel_id' => $hotel_id])->all());

        return new ActiveDataProvider([
            'query' => $modelClass::find()
                ->joinWith('hotel')
//                ->joinWith('facilities')
                ->where($params)
        ]);
    }
}
