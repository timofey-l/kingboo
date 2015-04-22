<?php

namespace common\components;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;
use yii\web\BadRequestHttpException;

class RoomImagesIndexAction extends IndexAction
{
    protected function prepareDataProvider()
    {
        $room_id = Yii::$app->request->get('room_id', false);
        $params = [
            'hotel.partner_id' => Yii::$app->user->id,
        ];

        if ($room_id !== false) {
            $params['room_id'] = (int) $room_id;
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        return new ActiveDataProvider([
            'query' => $modelClass::find()
                ->asArray()
                ->joinWith('room.hotel')
                ->where($params)
        ]);
    }
    
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        
        $room_id = Yii::$app->request->get('room_id', false);
        $params = [
            'hotel.partner_id' => Yii::$app->user->id,
        ];

        if ($room_id !== false) {
            $params['room_id'] = (int) $room_id;
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        $images = $modelClass::find()
                ->joinWith('room.hotel')
                ->where($params)
                ->all(); 
            
        foreach ($images as $k=>$img) {
            $images[$k] = $img->toArray();
        }
        return($images); 
    }

}
