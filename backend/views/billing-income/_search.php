<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BillingIncomeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-income-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sum') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'invoice_id') ?>

    <?php // echo $form->field($model, 'currency_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('billing_income', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('billing_income', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
