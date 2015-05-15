<?php
namespace partner\controllers;

use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

class RoomsController extends ActiveController
{

    public $modelClass = 'common\models\Room';

    public function actions()
    {
        return [
            'index' => [
                'class' => 'partner\components\RoomsIndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'partner\components\RoomsCreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'partner\components\RoomsUpdateAction',
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
    * Возвращает полный список особенностей номера (независимо от того, есть они у этого номера или нет)
    * 
    */
    public function actionFacilities() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $room_id = \Yii::$app->request->get('room_id',false);
        if ($room_id) {
            $checked = $this->findModel($room_id)->facilityArray();
        } else {
            $checked = [];
        }
        return \common\models\RoomFacilities::options(false,$checked);
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

    protected function findModel($id)
    {
        if (($model = \common\models\Room::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}