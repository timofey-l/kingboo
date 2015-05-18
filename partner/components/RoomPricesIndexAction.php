<?php

namespace partner\components;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;
use yii\web\BadRequestHttpException;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RoomPricesIndexAction extends IndexAction
{
    protected function prepareDataProvider()
    {
        $room_id = Yii::$app->request->get('room_id', false);
        $date_begin = trim(Yii::$app->request->get('startDate', false));
        $date_end = trim(Yii::$app->request->get('endDate', false));
        
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;
        $query = $modelClass::find()
                ->joinWith('room.hotel')
                ->where(['hotel.partner_id' => Yii::$app->user->id, 'room_id' => $room_id])
                ->andWhere(['>=','date',$date_begin])
                ->andWhere(['<=','date',$date_end]);        

        //var_dump($modelClass::find()->asArray()->joinWith('room.hotel')->where($params)->all());
        //var_dump($modelClass::find()->joinWith('room.hotel')->where($params));
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
    }
}
