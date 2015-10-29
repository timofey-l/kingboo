<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CallsStats */
/* @var $form ActiveForm */
?>
<div class="call-stats-_form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'company_name') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'contact_person') ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Готово!'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- call-stats-_form -->
