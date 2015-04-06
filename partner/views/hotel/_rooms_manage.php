<?php

$angularAppUrl = \partner\assets\RoomsManageAsset::register($this)->baseUrl;

/* @var $hotel \common\models\Hotel */
?>
<script>
    const BASE_URL = '<?= $angularAppUrl ?>';
    const LANG = '<?= \common\models\Lang::$current->url ?>';
</script>
<div ng-app="RoomsManageApp" ng-init="hotelId = <?= $hotel->id ?>;">
    <div class="box" ng-view>

    </div>
    <!-- /.box -->
</div>