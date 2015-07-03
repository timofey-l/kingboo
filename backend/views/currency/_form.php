<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="currency-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'name_en')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 3]) ?>

    <?= $form->field($model, 'symbol')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'format')->textInput(['maxlength' => 50]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend_models', 'Create') : Yii::t('backend_models', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
