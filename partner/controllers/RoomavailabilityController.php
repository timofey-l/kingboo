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
        if (!$count & $count !== 0) {
            $count = false;
        }
        $prices = \Yii::$app->request->post('prices', false);
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

        $update = [];
        if ($availability) {
            foreach ($availability as $a) {
                $update[] = $a->date;
            }
        }

        // Обновляем имеющиеся
        \common\models\RoomAvailability::groupUpdate($room, $startDate, $endDate, $count, $stopSale);

        // Вставляем недостающие
        \common\models\RoomAvailability::groupInsert($room, $startDate, $endDate, $count, $stopSale, $update);

        // Меняем цены
        if ($prices) {
            foreach ($prices as $newPrice) {
                // Если цена не задана, пропускаем этот пункт
                if (!$newPrice['price']) {
                    continue;
                }
                // Удаляем старые цены
                \common\models\RoomPrices::groupDelete($room, $startDate, $endDate, $newPrice['adults'], $newPrice['children'], $newPrice['kids']);
                // Записываем новые цены
                \common\models\RoomPrices::groupInsert($room, $startDate, $endDate, $newPrice['adults'], $newPrice['children'], $newPrice['kids'], $newPrice['price']);
            }
        }

        // Возвращаем данные на выбранный период
        $dates = [];
        $date = \DateTime::createFromFormat('Y-m-d', $startDate);
        $to = \DateTime::createFromFormat('Y-m-d', $endDate);
        while ($date <= $to) {
            $dates[$date->format('Y-m-d')] = \common\components\BookingHelper::getPrice(['date' => $date->format('Y-m-d'), 'roomId' =>$room->id]);
            if ($count !== false || $stopSale !== false) {
                // Поле, в котором хранится список всех дат, для которых произошли изменения в таблице availability
                $dates['changed'][] = $date->format('Y-m-d');
            }
            $date->modify('+1 day');
        }

        return $dates;
    }

    /**
     * Возвращает цены для указанных дат
     */
    public function actionPrices() {
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
            $dates[$date->format('Y-m-d')] = \common\components\BookingHelper::getPrice(['date' => $date->format('Y-m-d'), 'roomId' =>$room->id]);
            $date->modify('+1 day');
        }

        $dates['titles'] = \common\components\BookingHelper::getPriceTitles(['date' => $date->format('Y-m-d'), 'roomId' =>$room->id]);
        $dates['currency'] = $hotel->currency_id;

        return $dates;
    }

    public function actionCurrencies() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $currencies = \common\models\Currency::find()->all();
        return $currencies;
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