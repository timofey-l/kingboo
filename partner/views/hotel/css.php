<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
$lang = \common\models\Lang::$current->url;

$hotel_title = $hotel->{'title_' . $lang};
$this->title = $hotel_title;

$ace = \Yii::$app->assetManager->publish('@bower/ace-builds/src-min-noconflict')[1];
$this->registerJsFile($ace.'/ace.js');
$this->registerJs("
    var editorLESS = ace.edit('editorLESS');
    editorLESS.setTheme('ace/theme/xcode');
    editorLESS.getSession().setMode('ace/mode/less');
    editorLESS.setFontSize('1.1em');
    var \$less = $('#less');
    editorLESS.getSession().on('change', function(){
        \$less.val(editorLESS.getSession().getValue());
    });

    var editorCSS = ace.edit('editorCSS');
    editorCSS.setTheme('ace/theme/xcode');
    editorCSS.getSession().setMode('ace/mode/css');
    editorCSS.setFontSize('1.1em');
    editorCSS.setReadOnly(true);
");

$this->params['breadcrumbs'][] = ['label' => $hotel_title, 'url' => ['view', 'id' => $hotel->id]];
$this->params['breadcrumbs'][] = Yii::t('hotels', 'Custom CSS');
?>

<?php $form = ActiveForm::begin(); ?>

<?= \yii\helpers\Html::a(
    '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_hotel', 'Back to hotel view'),
    ['view', 'id' => $hotel->id],
    ['class' => 'btn btn-default']) ?>
    &nbsp;
    &nbsp;
<?= Html::button('<i class="fa fa-hdd-o"></i> '.\Yii::t('partner_css', 'Save'), [
    'class' => 'btn btn-success',
    'type' => 'submit',
]) ?>
<br/>
<br/>
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('partner_css', 'LESS code', []) ?></h3>
            </div>
            <div class="box-body">
                <textarea name="less" id="less" style="visibility: hidden;position: absolute;"><?= $hotel->less ?></textarea>
                <pre id="editorLESS" style="min-height:500px; height: 100%"><?= $hotel->less ?></pre>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('partner_css', 'Compiled css code (after last save)', []) ?></h3>
                <div class="box-tools pull-right">
                    <span class="label label-danger"><?= \Yii::t('partner_css', 'read only', []) ?></span>
                </div>
            </div>
            <div class="box-body">
                <pre id="editorCSS" style="min-height:500px; height: 100%"><?= $hotel->css ?></pre>
            </div>
        </div>
    </div>
</div>

<?= Html::button('<i class="fa fa-hdd-o"></i> '.\Yii::t('partner_css', 'Save'), [
    'class' => 'btn btn-success',
    'type' => 'submit',
]) ?>

<?php ActiveForm::end(); ?>