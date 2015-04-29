<?php

namespace partner\components;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;
use yii\web\BadRequestHttpException;

class HotelImagesIndexAction extends IndexAction
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
                ->asArray()
                ->joinWith('hotel')
                ->where($params)
        ]);
    }
    
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        
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
        $images = $modelClass::find()
                ->joinWith('hotel')
                ->where($params)
                ->all(); 
            
        foreach ($images as $k=>$img) {
            $images[$k] = $img->toArray();
        }
        return($images); 

       /* return  $modelClass::find()
                ->joinWith('hotel')
                ->where($params)
                ->asArray()->all();   */
    }

}
