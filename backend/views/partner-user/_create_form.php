<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model partner\models\PartnerUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'username')->textInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off']) ?>

	<?= $form->field($model, 'shopId')->textInput(['autocomplete' => 'off']) ?>

	<?= $form->field($model, 'shopPassword')->textInput(['autocomplete' => 'off']) ?>

	<?= $form->field($model, 'scid')->textInput(['autocomplete' => 'off']) ?>

	<?= $form->field($model, 'lang')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Lang::find()->all(), 'url', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend_models', 'Create')) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
