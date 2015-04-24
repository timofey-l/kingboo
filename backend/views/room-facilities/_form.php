<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RoomFacilities */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="room-facilities-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'name_en')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'important')->textInput() ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('room_facilities', 'Create') : Yii::t('room_facilities', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
