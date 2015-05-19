<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model partner\models\WidgetSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="widget-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'hotel_id') ?>

    <?= $form->field($model, 'hash_code') ?>

    <?= $form->field($model, 'params') ?>

    <?= $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'compiled_js') ?>

    <?php // echo $form->field($model, 'compiled_css') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('partner_widget', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('partner_widget', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
