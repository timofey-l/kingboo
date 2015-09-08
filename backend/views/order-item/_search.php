<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'room_id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'adults') ?>

    <?= $form->field($model, 'children') ?>

    <?php // echo $form->field($model, 'kids') ?>

    <?php // echo $form->field($model, 'sum') ?>

    <?php // echo $form->field($model, 'guest_name') ?>

    <?php // echo $form->field($model, 'guest_surname') ?>

    <?php // echo $form->field($model, 'guest_address') ?>

    <?php // echo $form->field($model, 'sum_currency_id') ?>

    <?php // echo $form->field($model, 'pay_sum') ?>

    <?php // echo $form->field($model, 'pay_sum_currency_id') ?>

    <?php // echo $form->field($model, 'payment_system_sum') ?>

    <?php // echo $form->field($model, 'payment_system_sum_currency_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend_order', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend_order', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
