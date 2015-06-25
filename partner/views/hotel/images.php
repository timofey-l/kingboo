<?php
\partner\assets\HotelImagesAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\HotelImages */

$directoryBower = Yii::$app->assetManager->getPublishedUrl('@bower');
$directoryLTE = $directoryBower . '/admin-lte';
$lang = \common\models\Lang::$current->url;

//Подключаем colorbox
$this->registerJsFile($directoryBower . '/colorbox/jquery.colorbox-min.js');
$this->registerJsFile($directoryBower . '/colorbox/i18n/jquery.colorbox-' . $lang . '.js');
$this->registerCssFile($directoryBower . '/colorbox/example1/colorbox.css', [], 'colorbox');

$lang = \common\models\Lang::$current->url;
$hotel_title = $model->{'title_' . $lang};
$this->title = $hotel_title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $hotel_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('hotels', 'Images');

//$o = new \common\models\HotelImage(); $o->deleteAll();
//echo \common\helpers\DebugHelper::grid(new \common\models\HotelImage());

?>

<div class="hotel-images">
    <div class="row" ng-app="ImagesManageApp" ng-init="hotelId = <?=$model->id?>;">
        <div class="box" ng-view>

        </div>
        <!-- /.box -->
    </div>
</div>
