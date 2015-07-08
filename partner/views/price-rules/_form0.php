<?php

use yii\bootstrap\ActiveForm;

/** @var \common\models\PriceRules $model */
/** @var \common\models\Room[] $rooms */
/** @var \yii\web\View $this */

$this->registerJsFile('/js/price-rules/add-form0.js', [
    'depends' => \partner\assets\AppAsset::className()
]);

$l = \common\models\Lang::$current->url;

$assetOptions = ['depends' => [
    \partner\assets\AppAsset::className(),
    \dmstr\web\AdminLteAsset::className(),
]];

// bootstrap modals


// iCheck plugin
$icheck = \Yii::$app->assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/iCheck');
//$this->registerCssFile($icheck[1] . '/all.css', $assetOptions);
$this->registerCssFile($icheck[1] . '/flat/flat.css', $assetOptions);
$this->registerCssFile($icheck[1] . '/flat/blue.css', $assetOptions);
$this->registerJsFile($icheck[1] . '/icheck.min.js', $assetOptions);

//DatePicker Bootstrap plugin
$datePicker = \Yii::$app->assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/datepicker');
$this->registerCssFile($datePicker[1] . '/datepicker3.css', $assetOptions);
$this->registerJsFile($datePicker[1] . '/bootstrap-datepicker.js', $assetOptions);
if (\common\models\Lang::$current->url != 'en')
    $this->registerJsFile($datePicker[1] . '/locales/bootstrap-datepicker.' . \common\models\Lang::$current->url . '.js', $assetOptions);

$dateFormat = \Yii::t('partner', 'dd/mm/yyyy');
?>

<?php $form = ActiveForm::begin([
    'enableClientScript' => false,
    'options' => [
        'id' => 'PriceRuleForm',
    ],
]); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= \Yii::t('partner_pricerules', 'Rooms') ?>
                </h3>
            </div>
            <div class="box-body">
                <?php foreach ($hotels as $index => $hotel): ?>
                    <?php /** @var \common\models\Hotel $hotel */ ?>

                    <h3><?= $hotel->{'title_' . $l} ?></h3>

                    <?php foreach ($hotel->rooms as $room): ?>
                        <div class="form-group field-pricerulebasic-additive">
                            <div class="checkbox">
                                <label class="">
                                    <?= \yii\helpers\Html::checkbox('rooms[]', false, [
                                        'class' => 'iCheck',
                                        'value' => $room->id,
                                    ]) ?>
                                    <?= $room->{'title_' . \common\models\Lang::$current->url} ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <br/>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= \Yii::t('partner_pricerules', 'Discount value') ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-6">
                        <?= $form->field($model, 'value') ?>
                    </div>
                    <div class="col-xs-6">
                        <div class="radio">
                            <label>
                                <input type="radio" name="PriceRuleBasic[valueType]" value="0" checked/>
                                <?= \Yii::t('partner_pricerules', 'percents') ?>
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="PriceRuleBasic[valueType]" value="1"/>
                                <?= \Yii::t('partner_pricerules', 'sum') ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?= \Yii::t('partner_pricerules', 'Options') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'additive')->checkbox([
                    'class' => 'iCheck',
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <input type="checkbox" class="iCheck" name="bookingRange"/>
                    <?= \Yii::t('partner_pricerules', 'Booking date range') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'dateFromB', ['template' => '{input}'])->hiddenInput() ?>
                <?= $form->field($model, 'dateToB', ['template' => '{input}'])->hiddenInput() ?>
                <div class="input-daterange input-group" data-provide="datepicker"
                     data-date-format="<?= $dateFormat ?>">
                    <input type="text" data-input="dateFromB" class="form-control" value="" disabled/>
                    <span class="input-group-addon"><?= \Yii::t('partner_pricerules', 'to') ?></span>
                    <input type="text" data-input="dateToB" class="form-control" value="" disabled/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <input type="checkbox" class="iCheck" name="livingRange"/>
                    <?= \Yii::t('partner_pricerules', 'Living date range') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'dateFrom', ['template' => '{input}'])->hiddenInput() ?>
                <?= $form->field($model, 'dateTo', ['template' => '{input}'])->hiddenInput() ?>
                <div class="input-daterange input-group" data-provide="datepicker"
                     data-date-format="<?= $dateFormat ?>">
                    <input type="text" data-input="dateFrom" class="form-control" value="" disabled/>
                    <span class="input-group-addon"><?= \Yii::t('partner_pricerules', 'to') ?></span>
                    <input type="text" data-input="dateTo" class="form-control" value="" disabled/>
                </div>
                <?= $form->field($model, 'applyForCheckIn')->checkbox([
                    'class' => 'iCheck',
                    'disabled' => 'disabled',
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <input type="checkbox" class="iCheck" name="minSum"/>
                    <?= \Yii::t('partner_pricerules', 'Minimum discount bound') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'minSum', [
                    'inputOptions' => [
                        'disabled' => 'disabled'
                    ]
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <input type="checkbox" class="iCheck" name="maxSum"/>
                    <?= \Yii::t('partner_pricerules', 'Maximum discount bound') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'maxSum', [
                    'inputOptions' => [
                        'disabled' => 'disabled'
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <input type="checkbox" class="iCheck" name="checkCode"/>
                    <?= \Yii::t('partner_pricerules', 'Check code') ?>
                </h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'code', [
                    'inputOptions' => [
                        'disabled' => 'disabled'
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <button type="submit" class="btn btn-primary"><?= \Yii::t('partner_pricerules', 'Create') ?></button>
    </div>
</div>

<?php ActiveForm::end(); ?>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= \Yii::t('partner_pricerules', 'Errors in form!') ?></h4>
            </div>
            <div class="modal-body">
                <div class="callout callout-danger lead message message-value-error">
                    <h4><?= \Yii::t('partner-pricerules', 'Value of discount is incorrect') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'Value of discount should be set and be greater than 0.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-rooms">
                    <h4><?= \Yii::t('partner-pricerules', 'Select rooms') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'You should select at least one room to apply discount.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-one-condition">
                    <h4><?= \Yii::t('partner-pricerules', 'Select condition') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'You should enable at least one condition to apply discount.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-dates-b">
                    <h4><?= \Yii::t('partner-pricerules', 'Booking date range') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'Dates in booking date range should be defined.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-dates-b-order">
                    <h4><?= \Yii::t('partner-pricerules', 'Booking date range') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'Start date range must be before the end date.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-dates-living">
                    <h4><?= \Yii::t('partner-pricerules', 'Living dates range') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'Dates in living date range should be defined.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-dates-order">
                    <h4><?= \Yii::t('partner-pricerules', 'Living dates range') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'Start date range must be before the end date.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-minsum">
                    <h4><?= \Yii::t('partner-pricerules', 'Minimum discount bound') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'Value of minimal sum discount bound should be set and be greater than 0.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-maxsum">
                    <h4><?= \Yii::t('partner-pricerules', 'Maximum discount bound') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'Value of maximal sum discount bound should be set and be greater than 0.') ?>
                    </p>
                </div>
                <div class="callout callout-danger lead message message-code">
                    <h4><?= \Yii::t('partner-pricerules', 'Code') ?></h4>

                    <p>
                        <?= \Yii::t('partner-pricerules', 'Discount code should be set. It should contain letters, digits and following chars: <code>!</code> <code>+</code> <code>-</code> <code>_</code>') ?>
                    </p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <?= \Yii::t('partner_pricerules', 'Correct errors') ?>
                </button>
            </div>
        </div>
    </div>
</div>