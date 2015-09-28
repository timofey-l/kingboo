<?php
use common\models\Lang;
use common\models\PayMethod;
use partner\models\PartnerUser;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var PartnerUser $partner */
/** @var PayMethod[] $payMethods */

$this->title = \Yii::t('billing_pay', 'Put money on your account');

$this->params['breadcrumbs'][] = $this->title;

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

$this->registerJs("$('input[type=radio]').iCheck({
            radioClass: 'iradio_flat-blue'
        });");

$this->registerJs("
window.payFormSubmitted = false;
$('#payform').submit(function(e) {
    if (window.payFormSubmitted) return;
    window.payFormSubmitted = true;
    e.preventDefault();
    var data = {
        sum: $('#sum').val(),
        payMethod: $('[name=payMethod]:checked').val()
    };

    $.ajax({
        method: 'POST',
        url: window.location.pathname,
        data: data,
        success: function(resp) {
            console.log(atob(resp));
            var respForm = $(atob(resp));
            $('body').append(respForm);
            respForm.submit();
        }
    }); 
});
", View::POS_END);

?>



<div class="box">
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'payform',
                'role' => 'form',
                'onsubmit' => 'return false',
            ]
        ]) ?>
            <div class="form-group" style="width: 150px;">
                <label for="sum"><?= \Yii::t('billing_pay', 'Sum to pay') ?></label>
                <div class="input-group">
                    <input type="number" min="1" class="form-control" id="sum" name="sum" placeholder="<?= \Yii::t('billing_pay', 'Sum') ?>" value="1000">
                    <div class="input-group-addon"><?= $partner->billing->currency->code ?></div>
                </div>
            </div>
            <div class="form-group">
                <label for="payMethod"><?= \Yii::t('billing_pay', 'Payment method') ?></label>
                    <?php foreach($payMethods as $i => $payMethod): ?>
                        <div>
                            <label style="font-weight: normal;">
                                <input type="radio" class="iCheck" name="payMethod" value="<?= $payMethod->id?>" <?= $i == 0 ? "checked" : ""?> >
                                <?= $payMethod->{'title_' . Lang::$current->url} ?>

                            </label>
                        </div>
                    <?php endforeach ?>
            </div>

            <button class="btn btn-primary"><?= \Yii::t('billing_pay', 'Pay') ?></button>
        <?php ActiveForm::end() ?>

    </div>
</div>