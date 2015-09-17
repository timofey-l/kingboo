<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model partner\models\PartnerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hotel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'partner_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'address_ru') ?>

    <?= $form->field($model, 'address_en') ?>

    <?= $form->field($model, 'lng') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'description_ru') ?>

    <?php // echo $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'timezone') ?>

    <?php // echo $form->field($model, 'description_en') ?>

    <?php // echo $form->field($model, 'title_ru') ?>

    <?php // echo $form->field($model, 'title_en') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('hotels', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('hotels', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
