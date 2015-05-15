<?php

/* @var $hotel \common\models\Hotel */

use \yii\helpers\Html;

$l = \common\models\Lang::$current->url;
$hotel_view_url = Yii::$app->urlManager->createUrl(['hotel/view', 'id' => $hotel->id]);
?>
<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title"><?= Html::a($hotel->{'title_' . $l}, ['hotel/view', 'id' => $hotel->id]) ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <a href="<?= $hotel_view_url ?>">
                    <img src="http://lorempixel.com/200/150/city/<?= rand(1, 10) ?>" alt=""/>
                </a>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <?php foreach (range(1,5) as $i): ?>
                            <i class="text-yellow <?= $i <= $hotel->category ? 'fa fa-star' : 'fa fa-star-o' ?>"></i>
                        <?php endforeach ?>
                    </div>
                    <div class="col-md-12">
                        <?= $hotel->address ?>
                    </div>
                    <div class="col-md-12">
                        <small><?= $hotel->{'description_' . $l} ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>
