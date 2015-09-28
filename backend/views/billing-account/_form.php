<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BillingAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'partner_id')->textInput() ?>

    <?= $form->field($model, 'balance')->textInput() ?>

    <?= $form->field($model, 'currency_id')->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('billing_account', 'Create') : Yii::t('billing_account', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
