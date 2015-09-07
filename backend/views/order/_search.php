<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'updated_at') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'contact_email') ?>

    <?php // echo $form->field($model, 'contact_phone') ?>

    <?php // echo $form->field($model, 'contact_name') ?>

    <?php // echo $form->field($model, 'contact_surname') ?>

    <?php // echo $form->field($model, 'contact_address') ?>

    <?php // echo $form->field($model, 'dateFrom') ?>

    <?php // echo $form->field($model, 'dateTo') ?>

    <?php // echo $form->field($model, 'sum') ?>

    <?php // echo $form->field($model, 'partial_pay')->checkbox() ?>

    <?php // echo $form->field($model, 'partial_pay_percent') ?>

    <?php // echo $form->field($model, 'pay_sum') ?>

    <?php // echo $form->field($model, 'hotel_id') ?>

    <?php // echo $form->field($model, 'lang') ?>

    <?php // echo $form->field($model, 'viewed')->checkbox() ?>

    <?php // echo $form->field($model, 'payment_url') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'partner_number') ?>

    <?php // echo $form->field($model, 'checkin_fullpay') ?>

    <?php // echo $form->field($model, 'payment_via_bank_transfer') ?>

    <?php // echo $form->field($model, 'additional_info') ?>

    <?php // echo $form->field($model, 'sum_currency_id') ?>

    <?php // echo $form->field($model, 'pay_sum_currency_id') ?>

    <?php // echo $form->field($model, 'payment_system_sum') ?>

    <?php // echo $form->field($model, 'payment_system_sum_currency_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend_order', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend_order', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
