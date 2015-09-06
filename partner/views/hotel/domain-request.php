<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$assetOptions = ['depends' => [
    \partner\assets\AppAsset::className(),
    \dmstr\web\AdminLteAsset::className(),
]];
// iCheck plugin
$icheck = \Yii::$app->assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/iCheck');
//$this->registerCssFile($icheck[1] . '/all.css', $assetOptions);
$this->registerCssFile($icheck[1] . '/flat/flat.css', $assetOptions);
$this->registerCssFile($icheck[1] . '/flat/blue.css', $assetOptions);
$this->registerJsFile($icheck[1] . '/icheck.min.js', $assetOptions);
$this->registerJs("
	$('.iCheck').iCheck({
        checkboxClass: 'icheckbox_flat-blue'
    });"
);

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */
$lang = \common\models\Lang::$current->url;
$hotel_title = $hotel->{'title_' . $lang};
$this->title = $hotel_title;

$this->params['breadcrumbs'][] = ['label' => $hotel_title, 'url' => ['view', 'id' => $hotel->id]];
$this->params['breadcrumbs'][] = Yii::t('domain-request', 'Domain registration request');
?>

<div class="domain-request">
<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-xs-12">
		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title"><?= Yii::t('domain-request', 'Domain registration request') ?></h3>
			</div>
			<div class="box-body">
				<?= \Yii::t('domain-request', '<p>Now your hotel Internet page of is located on the subdomain <i>{site}</i>. You can place it on your own domain in the domain zone <i>com</i>. To do this, fill out the form below. If you already have your personal domain, just fill out the domain name here. If you don&acute;t have domain, write here the preferred domain name and we will register it. In case this name is no more vacant, we will help you to choose a different name.</p> <p><span class="text-red">Attention!</span> By submitting this application, you automatically activate "My Domain" the service for 199 <i class="fa fa-rub"></i> monthly.</p>', ['site' => \Yii::$app->params['mainDomainShort']]) ?>
				<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
				<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
				<?= $form->field($model, 'domain_exists')->checkbox([
                    'class' => 'iCheck',
                ]) ?>
				<?= $form->field($model, 'domain')->textInput([
                    'maxlength' => 255,
                    'data-toggle' => 'popover',
                    'data-trigger' => 'hover focus',
                    'data-html' => 'true',
                    'data-container' => "body",
                    'data-placement' => "auto bottom",
                    'data-content' => $model->attributePopover('domain'),
                ]) ?>
				<?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
        		<?= Html::submitButton(Yii::t('domain-request', 'Send request'), ['class' => 'btn btn-success pull-right']) ?>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>
</div>