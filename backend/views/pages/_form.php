<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */

$ace = \Yii::$app->assetManager->publish('@bower/ace-builds/src-min-noconflict')[1];
$this->registerJsFile($ace.'/ace.js');
$this->registerJs("
    var editorRU = ace.edit('page-content_ru');
    editorRU.setTheme('ace/theme/xcode');
    editorRU.getSession().setMode('ace/mode/html');
    editorRU.setFontSize('1.1em');

    var editorEN = ace.edit('page-content_en');
    editorEN.setTheme('ace/theme/xcode');
    editorEN.getSession().setMode('ace/mode/html');
    editorEN.setFontSize('1.1em');

");

$this->registerCss("
    .ace_editor {
        min-height: 50em;
    }
");
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content_ru')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content_en')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
