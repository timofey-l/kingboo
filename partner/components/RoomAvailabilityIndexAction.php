<?php

namespace partner\components;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;
use yii\web\BadRequestHttpException;

class RoomAvailabilityIndexAction extends IndexAction
{
    protected function prepareDataProvider()
    {
        $room_id = Yii::$app->request->get('room_id', false);
        $startMonth = trim(Yii::$app->request->get('startMonth', false));
        $endMonth = trim(Yii::$app->request->get('endMonth', false));
        
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;
        $query = $modelClass::find()
                ->joinWith('room.hotel')
                ->where(['hotel.partner_id' => Yii::$app->user->id, 'room_id' => $room_id])
                ->andWhere(['>=','date',$startMonth])
                ->andWhere(['<=','date',$endMonth]);        

        //var_dump($modelClass::find()->asArray()->joinWith('room.hotel')->where($params)->all());
        //var_dump($modelClass::find()->joinWith('room.hotel')->where($params));
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
    }
}
