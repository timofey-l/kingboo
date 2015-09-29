<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pay-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'checked')->checkbox() ?>

    <?= $form->field($model, 'payed')->checkbox() ?>

    <?= $form->field($model, 'order_number') ?>

    <?= $form->field($model, 'invoiceId') ?>

    <?php // echo $form->field($model, 'customerNumber') ?>

    <?php // echo $form->field($model, 'orderCreatedDatetime') ?>

    <?php // echo $form->field($model, 'paymentDatetime') ?>

    <?php // echo $form->field($model, 'orderSumAmount') ?>

    <?php // echo $form->field($model, 'orderSumCurrencyPaycash') ?>

    <?php // echo $form->field($model, 'orderSumBankPaycash') ?>

    <?php // echo $form->field($model, 'shopSumAmount') ?>

    <?php // echo $form->field($model, 'shopSumCurrencyPaycash') ?>

    <?php // echo $form->field($model, 'shopSumBankPaycash') ?>

    <?php // echo $form->field($model, 'paymentPayerCode') ?>

    <?php // echo $form->field($model, 'paymentType') ?>

    <?php // echo $form->field($model, 'postParams') ?>

    <?php // echo $form->field($model, 'shopId') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('partner_pays', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('partner_pays', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
