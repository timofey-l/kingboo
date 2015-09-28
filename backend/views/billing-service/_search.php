<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BillingServiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-service-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name_ru') ?>

    <?= $form->field($model, 'description_ru') ?>

    <?= $form->field($model, 'archived')->checkbox() ?>

    <?= $form->field($model, 'default')->checkbox() ?>

    <?php // echo $form->field($model, 'monthly')->checkbox() ?>

    <?php // echo $form->field($model, 'unique')->checkbox() ?>

    <?php // echo $form->field($model, 'currency_id') ?>

    <?php // echo $form->field($model, 'enable_cost') ?>

    <?php // echo $form->field($model, 'monthly_cost') ?>

    <?php // echo $form->field($model, 'name_en') ?>

    <?php // echo $form->field($model, 'description_en') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('billing_service', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('billing_service', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
