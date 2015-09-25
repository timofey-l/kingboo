<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BillingInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'sum') ?>

    <?= $form->field($model, 'currency_id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'payed')->checkbox() ?>

    <?php // echo $form->field($model, 'system') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('billing_invoice', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('billing_invoice', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
