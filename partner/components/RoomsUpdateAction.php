<?php

namespace partner\components;

use Yii;
use yii\rest\Action;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * RoomsCreateAction в отличие от CreateAction позволяет сохранять вместе со свойствами номера
 *
 */
class RoomsUpdateAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;


    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        } else {
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
        }

        return $model;
    }
}
