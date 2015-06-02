<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model partner\models\PartnerUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

	<?= $form->field($model, 'shopId')->textInput(['autocomplete' => 'off']) ?>

	<?= $form->field($model, 'shopPassword')->textInput(['autocomplete' => 'off']) ?>

	<?= $form->field($model, 'scid')->textInput(['autocomplete' => 'off']) ?>

	<div class="form-group">
        <?= Html::submitButton($model->scenario == "create" ? Yii::t('backend_models', 'Create') : Yii::t('backend_models', 'Update'), ['class' => $model->scenario == "create" ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
