<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BillingPaysYandex */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-pays-yandex-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'invoiceId')->textInput() ?>

    <?= $form->field($model, 'payed')->checkbox() ?>

    <?= $form->field($model, 'checked')->checkbox() ?>

    <?= $form->field($model, 'check_post_dump')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'avisio_post_dump')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'billing_invoice_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('billing_pays_yandex', 'Create') : Yii::t('billing_pays_yandex', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
