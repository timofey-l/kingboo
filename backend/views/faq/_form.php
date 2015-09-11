<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */
/* @var $form yii\widgets\ActiveForm */

$ace = \Yii::$app->assetManager->publish('@bower/ace-builds/src-min-noconflict')[1];
$this->registerJsFile($ace.'/ace.js');
$this->registerJs("
    var content_ru = $('#faq-content_ru');
    var editorRU = ace.edit('content_ru');
    editorRU.getSession().setValue(content_ru.val())
    editorRU.setTheme('ace/theme/xcode');
    editorRU.getSession().setMode('ace/mode/html');
    editorRU.setFontSize('1.1em');
    editorRU.getSession().on('change', function(){
        content_ru.val(editorRU.getSession().getValue());
    });

    var content_en = $('#faq-content_en');
    var editorEN = ace.edit('content_en');
    editorEN.setTheme('ace/theme/xcode');
    editorEN.getSession().setMode('ace/mode/html');
    editorEN.getSession().setValue(content_en.val())
    editorEN.setFontSize('1.1em');
    editorEN.getSession().on('change', function(){
        content_en.val(editorEN.getSession().getValue());
    });
");

$this->registerCss("
    .ace_editor {
        min-height: 50em;
    }
");
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content_ru')->hiddenInput() ?>
    <div id="content_ru"></div>

    <?= $form->field($model, 'content_en')->hiddenInput() ?>
    <div id="content_en"></div>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('faq', 'Create') : Yii::t('faq', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
