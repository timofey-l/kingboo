<?php
namespace partner\controllers;

use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use common\models\RoomPrices;

class RoompricesController extends ActiveController
{

    public $modelClass = 'common\models\RoomPrices';

    public function behaviors()
    {
        $a = parent::behaviors();
        $b = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'updategroup' => ['post'],
                ],
            ],
        ];
        return \yii\helpers\ArrayHelper::merge($a,$b);
    }
    
    public function actions()
    {
        return [
            'index' => [
                'class' => 'common\components\RoompricesIndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'yii\rest\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }
    
    /**
    * Групповое изменение цен с startDate до endDate для room_id
    * Если TYPE_GUESTS нужны еще adults, children, kids
    */
    public function actionUpdategroup() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $startDate = \Yii::$app->request->post('startDate', false);
        $endDate = \Yii::$app->request->post('endDate', false);
        $room_id = \Yii::$app->request->post('room_id', false);
        $adults = \Yii::$app->request->post('adults', false);
        $children = \Yii::$app->request->post('children', false);
        $kids = \Yii::$app->request->post('kids', false);
        $price = \Yii::$app->request->post('price', false);
        
        $room = \common\models\Room::findOne($room_id);
        if (!$room_id || !$startDate || !$endDate) {
            return [];
        }
        if (!$room->price_type == \common\components\ListPriceType::TYPE_GUESTS && (!$adults || $children === false || $kids === false)) {
            return [];
        }
        $hotel = \common\models\Hotel::findOne($room->hotel_id);
        
        //CheckAccess
        if (\Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }
        if ($hotel->partner_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
            
        $prices = RoomPrices::find()
            ->where(['room_id' => $room_id, 'adults' => $adults, 'children' => $children, 'kids' => $kids])
            ->andWhere(['>=', 'date', $startDate])
            ->andWhere(['<=', 'date', $endDate])
            ->all();
         
        //Записываем существующие цены   
        $updated = $saved = [];
        if ($prices) {
            foreach ($prices as $p) {
                if ($price == 0) {
                    if ($p->delete()) {
                        $saved[] = $p->date;
                    }
                } else {
                    $p->price = $price;
                    if ($p->save()) {
                        $saved[] = $p->date;
                    }
                    $updated[] = $p->date;
                }
            }
        }
        
        if ($price == 0) {
            return $saved;
        }
        
        //Добавляем цены, которых не было
        $date = \DateTime::createFromFormat('Y-m-d', $startDate);
        $to = \DateTime::createFromFormat('Y-m-d', $endDate);   
        while ($date <= $to) {
            if (in_array($date->format('Y-m-d'),$updated)) {
                $date->modify('+1 day');
                continue;
            }   
            $p = new RoomPrices();
            $p->date = $date->format('Y-m-d');
            $p->room_id = $room_id;
            $p->adults = $adults;
            $p->children = $children;
            $p->kids = $kids;
            $p->price = $price;
            $p->price_currency = $hotel->currency_id;
            if ($p->save()) {
                $saved[] = $p->date;
            }
            $date->modify('+1 day');
        }

        return $saved;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (\Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }

        if ($model !== null) {
            if ($model->room->hotel->partner_id != \Yii::$app->user->id) {
                throw new ForbiddenHttpException();
            }
        }
    }
}