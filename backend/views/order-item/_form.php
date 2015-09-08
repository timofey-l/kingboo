<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'room_id')->textInput() ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'adults')->textInput() ?>

    <?= $form->field($model, 'children')->textInput() ?>

    <?= $form->field($model, 'kids')->textInput() ?>

    <?= $form->field($model, 'guest_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'guest_surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'guest_address')->textInput() ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'sum_currency_id')->textInput() ?>

    <?= $form->field($model, 'pay_sum')->textInput() ?>

    <?= $form->field($model, 'pay_sum_currency_id')->textInput() ?>

    <?= $form->field($model, 'payment_system_sum')->textInput() ?>

    <?= $form->field($model, 'payment_system_sum_currency_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend_order', 'Create') : Yii::t('backend_order', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
