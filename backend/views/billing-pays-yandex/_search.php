<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BillingPaysYandexSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-pays-yandex-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'invoiceId') ?>

    <?= $form->field($model, 'payed')->checkbox() ?>

    <?= $form->field($model, 'checked')->checkbox() ?>

    <?= $form->field($model, 'check_post_dump') ?>

    <?php // echo $form->field($model, 'avisio_post_dump') ?>

    <?php // echo $form->field($model, 'billing_invoice_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('billing_pays_yandex', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('billing_pays_yandex', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
