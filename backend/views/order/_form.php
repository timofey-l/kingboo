<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'contact_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_address')->textInput() ?>

    <?= $form->field($model, 'dateFrom')->textInput() ?>

    <?= $form->field($model, 'dateTo')->textInput() ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'partial_pay')->checkbox() ?>

    <?= $form->field($model, 'partial_pay_percent')->textInput() ?>

    <?= $form->field($model, 'pay_sum')->textInput() ?>

    <?= $form->field($model, 'hotel_id')->textInput() ?>

    <?= $form->field($model, 'lang')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'viewed')->checkbox() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'checkin_fullpay')->textInput() ?>

    <?= $form->field($model, 'payment_via_bank_transfer')->textInput() ?>

    <?= $form->field($model, 'additional_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sum_currency_id')->textInput() ?>

    <?= $form->field($model, 'pay_sum_currency_id')->textInput() ?>

    <?= $form->field($model, 'payment_system_sum')->textInput() ?>

    <?= $form->field($model, 'payment_system_sum_currency_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend_order', 'Create') : Yii::t('backend_order', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
