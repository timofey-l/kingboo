<?php
namespace partner\controllers;

use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

class HotelimagesController extends ActiveController
{

    public $modelClass = 'common\models\HotelImage';

    public function actions()
    {
        return [
            'index' => [
                'class' => 'common\components\HotelImagesIndexAction',
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

    public function checkAccess($action, $model = null, $params = [])
    {                     
        if (\Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }

        if ($model !== null) {
            if ($model->hotel->partner_id != \Yii::$app->user->id) {
                throw new ForbiddenHttpException();
            }
        }
    }
}