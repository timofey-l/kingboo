<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BillingService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-service-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'archived')->checkbox() ?>

    <?= $form->field($model, 'default')->checkbox() ?>

    <?= $form->field($model, 'monthly')->checkbox() ?>

    <?= $form->field($model, 'unique')->checkbox() ?>

    <?= $form->field($model, 'enable_cost')->textInput() ?>

    <?= $form->field($model, 'monthly_cost')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('billing_service', 'Create') : Yii::t('billing_service', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
