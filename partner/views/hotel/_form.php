<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Currency;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */
/* @var $form yii\widgets\ActiveForm */

$langs = \common\models\Lang::sortedLangList();   

\Yii::$app->assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/iCheck');
\Yii::$app->assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/bootstrap-slider');

$dir_iCheck  = \Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/plugins/iCheck');
$dir_slider  = \Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/plugins/bootstrap-slider');

// подключаем плагины iCheck и slider
$this->registerJsFile($dir_iCheck . '/icheck.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerCssFile($dir_iCheck . '/minimal/blue.css');
$this->registerJsFile($dir_slider . '/bootstrap-slider.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerCssFile($dir_slider . '/slider.css', ['depends' => \yii\web\JqueryAsset::className()]);


$this->registerJs("
$('.icheck').iCheck({
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
");

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
                    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'domain')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'contact_email')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'currency_id')->dropDownList(Currency::getOptions('code',true)) ?>
                    
                    <?= $form->field($model, 'category')->dropDownList([1=>1,2=>2,3=>3,4=>4,5=>5]) ?>

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
                    <h3 class="box-title"><?= Yii::t('hotels', 'Title') ?></h3>
                </div>
                <div class="box-body">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <li class="<?= $i == 0 ? "active" : "" ?>">
                                    <a href="#title_<?= $lang->url ?>" data-tabid="title_<?= $lang->url ?>" data-toggle="tab" >

                                        <?= strtoupper($lang->url) ?>

                                    </a>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                        <div class="tab-content">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <div class="tab-pane <?= $i == 0 ? "active" : "" ?>" id="title_<?= $lang->url ?>">

                                    <?= $form->field($model, 'title_' . $lang->url, [
                                        'template' => '{input}{error}'
                                    ])->textInput(['rows' => 6]) ?>

                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>

                </div>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= Yii::t('hotels', 'Description') ?></h3>
                </div>
                <div class="box-body">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <li class="<?= $i == 0 ? "active" : "" ?>">
                                    <a href="#description_<?= $lang->url ?>" data-tabid="description_<?= $lang->url ?>" data-toggle="tab" >

                                        <?= strtoupper($lang->url) ?>

                                    </a>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                        <div class="tab-content">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <div class="tab-pane <?= $i == 0 ? "active" : "" ?>" id="description_<?= $lang->url ?>">

                                    <?= $form->field($model, 'description_' . $lang->url, [
                                        'template' => '{input}{error}'
                                    ])->textarea(['rows' => 6]) ?>

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
        <?= Html::submitButton($model->isNewRecord ? Yii::t('hotels', 'Create') : Yii::t('hotels', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
