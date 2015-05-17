<?php
\partner\assets\RoomsManageAsset::register($this);

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$lang = \common\models\Lang::$current->url;
$directoryBower = Yii::$app->assetManager->getPublishedUrl('@bower');
$directoryLTE = $directoryBower . '/admin-lte';
$this->registerJsFile($directoryBower . '/moment/moment.js');
if ($lang != 'en') {
    $this->registerJsFile($directoryBower . '/moment/locale/' . $lang . '.js');    
}
$this->registerJsFile($directoryBower . '/underscore/underscore.js');
$this->registerJsFile('/js/daterangepicker.js');
$this->registerCssFile('/css/daterangepicker-bs3.css', [], 'daterangepicker');

//Подключаем colorbox
$this->registerJsFile($directoryBower . '/colorbox/jquery.colorbox-min.js');
$this->registerJsFile($directoryBower . '/colorbox/i18n/jquery.colorbox-'.$lang.'.js');
$this->registerCssFile($directoryBower . '/colorbox/example1/colorbox.css',[],'colorbox');

$lang = \common\models\Lang::$current->url;
$hotel_title = $model->{'title_' . $lang};
$this->title = $hotel_title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $hotel_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Rooms'), 'url' => ['rooms', 'id' => $model->id]];

//echo \common\helpers\DebugHelper::grid(new \common\models\RoomFacilities());

?>
<script type="text/javascript">
    const LANG = '<?= \common\models\Lang::$current->url ?>';
    const PriceTypes = <?= \common\components\ListPriceType::getJsObjects() ?>;
    const PriceTypeFixed = <?= \common\components\ListPriceType::TYPE_FIXED ?>;
    const PriceTypeGuests = <?= \common\components\ListPriceType::TYPE_GUESTS ?>;
    const CURRENCY = <?= $model->currency_id ?>;
</script>

<div class="hotel-view">
    <div ng-app="RoomsManageApp" ng-init="hotelId = <?= $model->id ?>;">
        <div class="box" ng-view>

        </div>
        <!-- /.box -->
    </div>
</div>
