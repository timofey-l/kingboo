<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Currency;
use partner\assets\RemodalAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */
/* @var $form yii\widgets\ActiveForm */

$langs = \common\models\Lang::sortedLangList(); 
$lang = \common\models\Lang::$current->url;  

\Yii::$app->assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/iCheck');
\Yii::$app->assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/bootstrap-slider');

$dir_iCheck  = \Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/plugins/iCheck');
$dir_slider  = \Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/plugins/bootstrap-slider');

// подключаем плагины iCheck и slider
$this->registerJsFile($dir_iCheck . '/icheck.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerCssFile($dir_iCheck . '/minimal/blue.css');
$this->registerJsFile($dir_slider . '/bootstrap-slider.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerCssFile($dir_slider . '/slider.css', ['depends' => \yii\web\JqueryAsset::className()]);

// подключаем remodal
RemodalAsset::register($this);

if ($model->isNewRecord) {
    $this->registerJs("
        var lang = '$lang';
        var newRecord = true;
    ", yii\web\View::POS_END);
} else {
    $this->registerJs("
        var lang = '$lang';
        var newRecord = false;
    ", yii\web\View::POS_END);
}

$this->registerJs("
$('#hotel-allow_partial_pay').iCheck({
 checkboxClass: 'icheckbox_minimal-blue'
}).on('ifChecked', function(event){
	$('.partial_pay_container').removeClass('hide');
    $('.partialSlider').slider('enable');
}).on('ifUnchecked', function(event){
    $('.partial_pay_container').addClass('hide');
    $('.partialSlider').slider('disable');
});

$('.partialSlider').slider({
	enabled: " . ($model->allow_partial_pay ? "true" : "false") . ",
	formatter: function(value) {
		$('#partial_pay_value').html(value);
		return value;
	}
});

$('#hotel-ru').iCheck({
 checkboxClass: 'icheckbox_minimal-blue'
}).on('ifChecked', function(event){
    $($('#hotel-ru').parent()).css('border', 'none');
    $($('#hotel-en').parent()).css('border', 'none');
    $('#submitBtn').prop('disabled', false);
}).on('ifUnchecked', function(event){
    if (!$('#hotel-en').parent().hasClass('checked')) {
        $($('#hotel-ru').parent()).css('border', 'solid 1px red');
        $($('#hotel-en').parent()).css('border', 'solid 1px red');
        $('#submitBtn').prop('disabled', true);
    }
});
$('#hotel-en').iCheck({
 checkboxClass: 'icheckbox_minimal-blue'
}).on('ifChecked', function(event){
    $($('#hotel-ru').parent()).css('border', 'none');
    $($('#hotel-en').parent()).css('border', 'none');
    $('#submitBtn').prop('disabled', false);
}).on('ifUnchecked', function(event){
    if (!$('#hotel-ru').parent().hasClass('checked')) {
        $($('#hotel-ru').parent()).css('border', 'solid 1px red');
        $($('#hotel-en').parent()).css('border', 'solid 1px red');
        $('#submitBtn').prop('disabled', true);
    }
});
if (newRecord) {
    $('#hotel-' + lang).iCheck('check');
}

// Подтверждение изменения валюты
window.remodal = $('#modal').remodal();
$(document).on('confirmation', '.remodal', function () {
    if ($('#submit_prompt').val() == 'change') {
        document.forms.w0.submit();
    } else {
        window.remodal.open();
    }
});
");

// Проверка на изменение валюты
$this->registerJs("
var oldCurrencyId = '" . $model['currency_id'] . "';
function checkCurrency() {
    if (oldCurrencyId != $('#hotel-currency_id').val()) {
        window.remodal.open();
        return false;
    }
    return true;
}
", yii\web\View::POS_END);

$this->registerCss('
.slider .slider-selection {
	background: #3c8dbc;
}
.tooltip.top {
  padding: 5px 0;
  margin-top: -27px;
}
.partial_pay_value {
	font-weight: bold;
	font-size: 110%;
}

.iCheck-helper {
	margin-right: 7px;
}
')
?>

<div class="hotel-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= Yii::t('hotels', 'Common information') ?></h3>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => 255,
                        'data-toggle' => 'popover',
                        'data-trigger' => 'hover focus',
                        'data-html' => 'true',
                        'data-container' => "body",
                        'data-placement' => "auto right",
                        'data-content' => $model->attributePopover('name'),
                    ]) ?>

                    <?php if ($model->domain) {
                            echo $form->field($model, 'domain')->textInput([
                            'maxlength' => 255,
                            'data-toggle' => 'popover',
                            'data-trigger' => 'hover focus',
                            'data-html' => 'true',
                            'data-container' => "body",
                            'data-placement' => "auto right",
                            'data-content' => $model->attributePopover('domain'),
                        ]);}
                    ?>

                    <?= $form->field($model, 'contact_email')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'currency_id')->dropDownList(Currency::getOptions('code',true), [
                        'maxlength' => 255,
                        'data-toggle' => 'popover',
                        'data-trigger' => 'hover focus',
                        'data-html' => 'true',
                        'data-container' => "body",
                        'data-placement' => "auto right",
                        'data-content' => $model->attributePopover('currency_id'),
                    ]) ?>
                    
                    <?= $form->field($model, 'category')->dropDownList([0=>\Yii::t('hotels', 'No category'),1=>1,2=>2,3=>3,4=>4,5=>5]) ?>

                    <?= $form->field($model, 'timezone')->dropDownList(array_combine(DateTimeZone::listIdentifiers(),DateTimeZone::listIdentifiers())) ?>

	                <div class="form-group">
		                <label>
		                    <?= Html::activeCheckbox($model, 'allow_partial_pay', [
			                    'class' => 'icheck',
		                    ]) ?>
		                    &nbsp;

		                </label>
		                <div class="partial_pay_container <?= $model->allow_partial_pay ? "" : "hide" ?>">
							<div class="partial_pay_value">
                                <?= Yii::t('hotels', 'Specify the minimum amount of partial advance payment') ?>: 
								<span id="partial_pay_value">
									<?= $model->partial_pay_percent ?>
								</span>%
							</div>
			                <?= Html::activeInput('text', $model, 'partial_pay_percent', [
				                'class' => 'partialSlider',
				                'data-slider-value' => $model->partial_pay_percent,
				                'data-slider-id' => 'partial_pay_percent_slider',
				                'data-slider-min' => \common\models\Hotel::MIN_PART_PAY,
				                'data-slider-max' => 100,
				                'data-slider-step' => 1,
			                ]) ?>
		                </div>
	                </div>

                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= Yii::t('hotels', 'Languages') ?></h3>
                </div>
                <div class="box-body">

                    <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>
                        <?= Html::activeCheckbox($model, $lang->url, [
                            'class' => 'icheck',
                            'template' => '{input}{error}',
                        ]) ?>
                        &nbsp;
                    <?php endforeach; ?>
                    <br /><br />

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <li class="<?= $i == 0 ? "active" : "" ?>">
                                    <a href="#lang_<?= $lang->url ?>" data-tabid="lang_<?= $lang->url ?>" data-toggle="tab" >

                                        <?= strtoupper($lang->url) ?>

                                    </a>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                        <div class="tab-content">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <div class="tab-pane <?= $i == 0 ? "active" : "" ?>" id="lang_<?= $lang->url ?>">

                                    <?= $form->field($model, 'title_' . $lang->url)->textInput(['maxlength' => 255]) ?>

                                    <?= $form->field($model, 'address_' . $lang->url)->textInput(['maxlength' => 255]) ?>

                                    <?= $form->field($model, 'description_' . $lang->url)->textarea(['rows' => 6]) ?>

                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
    </div>



    <div class="form-group">
        <?php if ($model->isNewRecord) : ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('hotels', 'Create') : Yii::t('hotels', 'Save'), 
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'submitBtn']) ?>
        <?php else : ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('hotels', 'Create') : Yii::t('hotels', 'Save'), 
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'submitBtn', 'onclick' => 'return checkCurrency();']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="remodal" id="modal">
        <button data-remodal-action="close" class="remodal-close"></button>
        <h1><?= Yii::t('hotels', 'Confirm changes') ?></h1>
        <p>
            <?= Yii::t('hotels', 'You have changed the hotel currency. All room prices will be deleted. If you sure you want to continue type "change".') ?>
        </p>
        <p><input id="submit_prompt"></p>
        <br>
        <button data-remodal-action="cancel" class="btn"><?= Yii::t('hotels', 'Cancel') ?></button>
        <button data-remodal-action="confirm" class="btn btn-success"><?= Yii::t('hotels', 'Ok') ?></button>
    </div>

</div>
