<?php

namespace partner\components;

use Yii;
use yii\rest\Action;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

/**
 * RoomsCreateAction в отличие от CreateAction позволяет сохранять вместе со свойствами номера
 *
 */
class RoomsCreateAction extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the name of the view action. This property is need to create the URL when the model is successfully created.
     */
    public $viewAction = 'view';


    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            //обновляем свойства
            $facilities = Yii::$app->request->post('facilities',false);
            if ($facilities) {
                //удаляем существующие связи
                $old = $model->facilities;
                foreach ($old as $f) {
                    $model->unlink('facilities', $f, true);
                }
                //добавляем новые связи
                foreach ($facilities as $k=>$facility) {
                    if (!$facility['checked']) continue;
                    if (($f = \common\models\RoomFacilities::findOne($facility['id'])) !== null) {
                        $model->link('facilities',$f);
                    }
                }
            }            

            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));

        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}
