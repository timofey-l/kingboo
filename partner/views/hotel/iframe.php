<?php
use common\models\Hotel;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $hotel Hotel */


$server = preg_replace('/^partner\./', '', \Yii::$app->request->serverName);

$lang = \common\models\Lang::$current->url;

$hotel_title = $hotel->{'title_' . $lang};
$this->title = $hotel_title;

$this->params['breadcrumbs'][] = ['label' => $hotel_title, 'url' => ['view', 'id' => $hotel->id]];
$this->params['breadcrumbs'][] = Yii::t('hotels', 'Frame on site');

$dir_iCheck = \Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/plugins/iCheck');
$dir_slider = \Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/plugins/bootstrap-slider');

// подключаем плагины iCheck и slider
$this->registerJsFile($dir_iCheck . '/icheck.min.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerCssFile($dir_iCheck . '/flat/blue.css');
$this->registerJs("
$('.icheck').iCheck({
 checkboxClass: 'icheckbox_flat-blue'
}).on('ifToggled', function(e){
    updateView();
});
$('.iradio').iCheck({
 radioClass: 'iradio_flat-blue'
}).on('ifToggled', function(e){
    updateView();
});
");
$this->registerJsFile($dir_slider . '/bootstrap-slider.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerCssFile($dir_slider . '/slider.css', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJs("
$('.heightSlider').slider({
    formatter: function(value) {
		$('#heightSlider_value').html(value);
		return value;
	},

}).on('slide', function(){
    updateView();
});");

// update view
$this->registerJs("
window.updateView = function() {
    // collect params
    var params = {};

    // hide description
    params.hide_desc = $('[name=hide_desc]').is(':checked');

    // set width 100%
    params.width100 = $('[name=width100]').is(':checked');

    // height value
    params.height = $('[name=height]').val();

    // domain
    params.domain = $('[name=domain]:checked').val();
    var partner_url = 'https://partner.king-boo.com/site/transform-to-https?url=';

    var style = ' style=\"';
    if (params.width100) {
        style += 'width:100%;';
    }
    style += 'height:' + params.height + 'px;';
    style += '\"';

    var url = params.domain + '%3Fembedded=1';
    var url = partner_url + url;
    if (params.hide_desc) {
        url += '&no_desc=1';
    }
    var iframe_text = '<iframe src=\"' + url + '\" frameborder=\"0\" ' + style + '></iframe>';

    $('#code').val(iframe_text);
    $('#iframe-target').html(iframe_text);
//    $('#iframe-target').append($(iframe_text));
}
updateView();
");
?>
<?= \yii\helpers\Html::a(
    '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_hotel', 'Back to hotel view'),
    ['view', 'id' => $hotel->id],
    ['class' => 'btn btn-default']) ?>
<br/>
<br/>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('partner_iframe', 'Frame on your site', []) ?></h3>
            </div>
            <div class="box-body">
                <p>
                    <?= \Yii::t('partner_iframe', 'You can place search form with hotel description on your site or page using <code>iframe</code> tag.', []) ?>
                </p>

                <div class="form-group">
                    <label for="hide_desc">
                        <input type="checkbox" name="hide_desc" class="icheck" data-param="hide_desc" onchange="updateView()">
                        <?= \Yii::t('partner_iframe', 'Hide description and photos', []) ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="width100">
                        <input type="checkbox" name="width100" class="icheck" data-param="width100" onchange="updateView()">
                        <?= \Yii::t('partner_iframe', 'Set width 100%', []) ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for=""><?= \Yii::t('partner_iframe', 'Height (px)', []) ?></label>

                    <div id="">
                        <span id="heightSlider_value"></span> px
                    </div>
                    <div class="slider-container" style="max-width: 300px">
                        <?= Html::input('text', 'height', 300, [
                            'class' => 'heightSlider',
                            'style' => 'max-width: 300px',
                            'data-slider-value' => 300,
                            'data-slider-id' => 'height_slider',
                            'data-slider-min' => 300,
                            'data-slider-max' => 900,
                            'data-slider-step' => 5,
                            'onchange' => "updateView()",
                        ]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= \Yii::t('partner_iframe', 'Domain', []) ?>:
                    <br><br>
                    <label class="basic-domain">
                        <input type="radio" checked name="domain" class="iradio"
                               value="http://<?= $hotel->name ?>.<?= $server ?>" onchange="updateView()">
                        http://<?= $hotel->name ?>.<?= $server ?>
                    </label>
                    <?php if ($hotel->domain): ?>
                        <br>
                        <label class="custom-domain">
                            <input type="radio" name="domain" class="iradio" value="http://<?= $hotel->domain ?>" onchange="updateView()">
                            http://<?= $hotel->domain ?>
                        </label>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('partner_iframe', 'Frame code', []) ?></h3>
            </div>
            <div class="box-body">
                <?= \Yii::t('partner_iframe', 'Copy this code and paste it into the corresponding place on your page:', []) ?>
                <br>
                <br>
                <textarea class="form-control" name="code" id="code" rows="10" readonly></textarea>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('partner_iframe', 'Frame preview', []) ?></h3>
            </div>
            <div id="iframe-target" class="box-body">

            </div>
        </div>
    </div>
</div>

