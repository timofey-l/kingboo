<?php

/* @var $this yii\web\View */

use common\models\Lang;
use common\models\PayMethod;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = \Yii::t('partner_profile', 'Profile settings');
$assetManager = \Yii::$app->assetManager;
$l = Lang::$current->url;
$local = Lang::$current->local;

$this->params['breadcrumbs'][] = \Yii::t('partner_profile', 'Profile settings');

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

$this->registerJs("$('input[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_flat-blue'
        });");
?>

<?php $form = \yii\bootstrap\ActiveForm::begin() ?>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= \Yii::t('partner_profile', 'Payment options') ?></h3>
                </div>
                <div class="box-body">
                    <?= $form->field($user, 'allow_checkin_fullpay')->checkbox([
                        'class' => 'iCheck',
                    ]) ?>
                    <?= $form->field($user, 'allow_payment_via_bank_transfer')->checkbox([
                        'class' => 'iCheck',
                    ]) ?>
                    <?= $form->field($user, 'currency_exchange_percent')->textInput([
                        'maxlength' => 255,
                        'data-toggle' => 'popover',
                        'data-trigger' => 'hover focus',
                        'data-html' => 'true',
                        'data-container' => "body",
                        'data-placement' => "auto right",
                        'data-content' => $user->attributePopover('currency_exchange_percent'),
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= \Yii::t('partner_profile', 'Password change') ?></h3>
                </div>
                <div class="box-body">
                    <?= $form->field($user, 'password')->label(false) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= \Yii::t('partner_profile', 'Yandex.Money payment setup') ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <?= $form->field($user, 'shopId') ?>
                            <?= $form->field($user, 'scid') ?>
                            <?= $form->field($user, 'shopPassword') ?>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <?= $form->field($user, 'payMethods')->checkboxList(ArrayHelper::map(PayMethod::find()->all(), 'id', 'title_' . $l), [
                                'itemOptions' => [
                                    'class' => 'iCheck',
                                ]
                            ])->label(false) ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--div class="col-sm-12 col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= \Yii::t('partner_profile', 'Configuration of IP telephony') ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <?php if($partner->prima_login == ''): ?>
                            <div class="alert alert-info alert-dismissable ">
                                <i class="icon fa fa-info"></i>
                                <?= \Yii::t('partner_profile', 'Setting of these parameters will allow your customers to make calls directly from the website.', []) ?>
                            </div>
                            <?php endif; ?>

                            <?= $form->field($user, 'phone') ?>
                            <?= $form->field($user, 'company_name') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div-->
        <div class="col-xs-12">
            <div class="form-group">
                <?= Html::submitButton(\Yii::t('partner_profile', 'Apply'), ['class' => 'btn btn-success']) ?>
                &nbsp;
                <?= Html::a(\Yii::t('partner_profile', 'Cancel'), ['/'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>

    </div>


<?php \yii\bootstrap\ActiveForm::end() ?>