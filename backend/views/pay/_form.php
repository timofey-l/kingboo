<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Pay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'checked')->checkbox() ?>

    <?= $form->field($model, 'payed')->checkbox() ?>

    <?= $form->field($model, 'order_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoiceId')->textInput() ?>

    <?= $form->field($model, 'customerNumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'orderCreatedDatetime')->textInput() ?>

    <?= $form->field($model, 'paymentDatetime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'orderSumAmount')->textInput() ?>

    <?= $form->field($model, 'orderSumCurrencyPaycash')->textInput() ?>

    <?= $form->field($model, 'orderSumBankPaycash')->textInput() ?>

    <?= $form->field($model, 'shopSumAmount')->textInput() ?>

    <?= $form->field($model, 'shopSumCurrencyPaycash')->textInput() ?>

    <?= $form->field($model, 'shopSumBankPaycash')->textInput() ?>

    <?= $form->field($model, 'paymentPayerCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paymentType')->textInput() ?>

    <?= $form->field($model, 'postParams')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'shopId')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('partner_pays', 'Create') : Yii::t('partner_pays', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
