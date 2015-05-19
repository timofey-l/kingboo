<?php
namespace partner\controllers;

use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use common\models\RoomAvailability;

class RoomavailabilityController extends ActiveController
{

    public $modelClass = 'common\models\RoomAvailability';

    public function behaviors()
    {
        $a = parent::behaviors();
        $b = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'updategroup' => ['post'],
                    'checkprices' => ['post'],
                ],
            ],
        ];
        return \yii\helpers\ArrayHelper::merge($a,$b);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => 'partner\components\RoomAvailabilityIndexAction',
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
    * Групповое изменение параметров доступности с startDate до endDate для room_id
    */
    public function actionUpdategroup() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $startDate = \Yii::$app->request->post('startDate', false);
        $endDate = \Yii::$app->request->post('endDate', false);
        $room_id = \Yii::$app->request->post('room_id', false);
        $count = \Yii::$app->request->post('count', false);
        $stopSale = \Yii::$app->request->post('stopSale', false);

        $room = \common\models\Room::findOne($room_id);
        if (!$room_id || !$startDate || !$endDate) {
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
            
        $availability = RoomAvailability::find()
            ->where(['room_id' => $room_id])
            ->andWhere(['>=', 'date', $startDate])
            ->andWhere(['<=', 'date', $endDate])
            ->all();

        //Записываем существующие цены   
        $updated = $saved = [];
        if ($availability) {
            foreach ($availability as $a) {
                if ($count !== false) $a->count = $count;
                if ($stopSale !== false) $a->stop_sale = $stopSale;
                if ($a->save()) {
                    $saved[] = $a->date;
                }
                $updated[] = $a->date;
            }
        }
        
        if ($count === false) {
            return $saved;
        }
        
        //Добавляем позиции, которых не было
        $date = \DateTime::createFromFormat('Y-m-d', $startDate);
        $to = \DateTime::createFromFormat('Y-m-d', $endDate);   
        while ($date <= $to) {
            if (in_array($date->format('Y-m-d'),$updated)) {
                $date->modify('+1 day');
                continue;
            }   
            $a = new RoomAvailability();
            $a->date = $date->format('Y-m-d');
            $a->room_id = $room_id;
            if ($count !== false) $a->count = $count;
            $a->stop_sale = 0;
            if ($a->save()) {
                $saved[] = $a->date;
            }
            $date->modify('+1 day');
        }

        return $saved;

    }

    /**
     * Проверяет установлены ли цены для указанных дат
     */
    public function actionCheckprices() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $startDate = \Yii::$app->request->post('startMonth', false);
        $endDate = \Yii::$app->request->post('endMonth', false);
        $room_id = \Yii::$app->request->post('room_id', false);

        $room = \common\models\Room::findOne($room_id);
        if (!$room_id || !$startDate || !$endDate) {
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
        
        $dates = [];
        $date = \DateTime::createFromFormat('Y-m-d', $startDate);
        $to = \DateTime::createFromFormat('Y-m-d', $endDate);
        while ($date <= $to) {
            if (\common\components\BookingHelper::isPriceSet(['date' => $date->format('Y-m-d'), 'roomId' =>$room->id])) {
                $dates[] = $date->format('Y-m-d');
            }
            $date->modify('+1 day');
        }

        return $dates;
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