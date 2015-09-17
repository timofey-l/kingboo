<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\HotelSearch */
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

    <?= //$form->field($model, 'address_ru') ?>

    <?= //$form->field($model, 'address_en') ?>

    <?= $form->field($model, 'lng') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'description_ru') ?>

    <?php // echo $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'timezone') ?>

    <?php // echo $form->field($model, 'description_en') ?>

    <?php echo $form->field($model, 'title_ru') ?>

    <?php echo $form->field($model, 'title_en') ?>

    <?php // echo $form->field($model, 'currency_id') ?>

    <?php // echo $form->field($model, 'allow_partial_pay')->checkbox() ?>

    <?php // echo $form->field($model, 'partial_pay_percent') ?>

    <?php // echo $form->field($model, 'domain') ?>

    <?php // echo $form->field($model, 'less') ?>

    <?php // echo $form->field($model, 'css') ?>

    <?php // echo $form->field($model, 'contact_phone') ?>

    <?php // echo $form->field($model, 'contact_email') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
