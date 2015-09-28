<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BillingExpenseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-expense-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sum') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'service_id') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'sum_currency_id') ?>

    <?php // echo $form->field($model, 'currency_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('billing_expense', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('billing_expense', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
