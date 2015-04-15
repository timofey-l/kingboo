<?php

\partner\assets\RoomsManageAsset::register($this);

/* @var $hotel \common\models\Hotel */

$directoryBower = Yii::$app->assetManager->getPublishedUrl('@bower');
$directoryLTE = $directoryBower . '/admin-lte';
$this->registerJsFile($directoryBower . '/moment/moment.js');
$this->registerJsFile('/js/daterangepicker.js');
$this->registerCssFile('/css/daterangepicker-bs3.css',[],'daterangepicker');

?>
<script>
    const LANG = '<?= \common\models\Lang::$current->url ?>';
    const PriceTypes = <?= \common\components\ListPriceType::getJsObjects() ?>;
    const PriceTypeFixed = <?= \common\components\ListPriceType::TYPE_FIXED ?>;
    const PriceTypeGuests = <?= \common\components\ListPriceType::TYPE_GUESTS ?>;
    const CURRENCY = <?= $hotel->currency_id ?>;
</script>
<div ng-app="RoomsManageApp" ng-init="hotelId = <?= $hotel->id ?>;">
    <div class="box" ng-view>

    </div>
    <!-- /.box -->
</div>