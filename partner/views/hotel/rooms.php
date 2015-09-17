<?php
\partner\assets\RoomsManageAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$lang = \common\models\Lang::$current->url;
$langs = \common\models\Lang::sortedLangList();

$directoryBower = Yii::$app->assetManager->getPublishedUrl('@bower');
$directoryLTE = $directoryBower . '/admin-lte';
$this->registerJsFile($directoryBower . '/moment/moment.js');
if ($lang != 'en') {
    $this->registerJsFile($directoryBower . '/moment/locale/' . $lang . '.js');
}
$this->registerJsFile($directoryBower . '/underscore/underscore.js');
$this->registerJsFile('/js/daterangepicker.js');
$this->registerCssFile('/css/daterangepicker-bs3.css', [], 'daterangepicker');

// angular-sanitize
$ang_sanitize = \Yii::$app->assetManager->publish('@vendor/bower/angular-sanitize');
$assetOptions = ['depends' => [\partner\assets\AngularAsset::className()]];
$this->registerJsFile($ang_sanitize[1] . '/angular-sanitize.js', $assetOptions);

//Подключаем colorbox
$this->registerJsFile($directoryBower . '/colorbox/jquery.colorbox-min.js');
$this->registerJsFile($directoryBower . '/colorbox/i18n/jquery.colorbox-' . $lang . '.js');
$this->registerCssFile($directoryBower . '/colorbox/example1/colorbox.css', [], 'colorbox');

$lang = \common\models\Lang::$current->url;
$hotel_title = $model->{'title_' . $lang};
$this->title = $hotel_title;

$this->params['breadcrumbs'][] = ['label' => $hotel_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Rooms'), 'url' => ['rooms', 'id' => $model->id]];

//echo '<pre>';print_r($langs);
//echo \common\helpers\DebugHelper::grid(new \common\models\RoomPrices());
//echo 'res='.\common\components\BookingHelper::priceSetStatistic(['beginDate'=>'2015-09-01','endDate'=>'2015-09-30','roomId'=>3]).'<br>';

?>
<script type="text/javascript">
    const PriceTypes = <?=\common\components\ListPriceType::getJsObjects()?>;
    const PriceTypeFixed = <?=\common\components\ListPriceType::TYPE_FIXED?>;
    const PriceTypeGuests = <?=\common\components\ListPriceType::TYPE_GUESTS?>;
    const CURRENCY = <?=$model->currency_id?>;
    var hotelLangs = {};
    <?php foreach ($langs as $l) : ?>
    hotelLangs.<?= $l->url ?> = <?= $model->{$l->url} ? 'true' : 'false' ?>;
    <?php endforeach; ?>
</script>

<div class="hotel-view">
    <?= \yii\helpers\Html::a(
        '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_hotel', 'Back to hotel view'),
        ['view', 'id' => $model->id],
        ['class' => 'btn btn-default']) ?>
    <br/>
    <br/>
    <div ng-app="RoomsManageApp" ng-init="hotelId = <?=$model->id?>;">
        <div class="box" ng-view>

        </div>
        <!-- /.box -->
    </div>
</div>
