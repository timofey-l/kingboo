<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use partner\assets\RemodalAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

// подключаем remodal
RemodalAsset::register($this);

$l = \common\models\Lang::$current->url;
$this->title = $model->{'title_' . $l};

$this->params['breadcrumbs'][] = $this->title;

// Подтверждение удаления
$this->registerJs("

window.remodal = $('#modal').remodal();
$(document).on('confirmation', '.remodal', function (e) {
    console.log(e);
    if ($('#submit_prompt').val() == 'delete') {
        $.post('" . Url::to(['delete', 'id' => $model->id]) . "');
    } else {
        window.remodal.open();
    }
});

");
$this->registerJs("
function promptDelete() {
    window.remodal.open();
    return false;
}
function promptFreeze() {
    window.remodal_freeze.open();
    return false;
}
", yii\web\View::POS_END);
?>
<style>
    .rooms-list {
        margin-left: 10px;
        margin-right: 10px;
    }

    .rooms-list:not(:last-child) {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #efefef;
    }
</style>
<div class="hotel-view">
    <p>
        <?= Html::a('<span class="fa fa-edit"></span>' . Yii::t('hotels', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-app']) ?>
        <?= Html::a('<span class="fa fa-check-square-o"></span>' . Yii::t('hotels', 'Facilities'), ['facilities', 'id' => $model->id], ['class' => 'btn btn-app']) ?>
        <?= Html::a('<span class="fa fa-hotel"></span>' . Yii::t('hotels', 'Rooms'), ['rooms', 'id' => $model->id], ['class' => 'btn btn-app']) ?>
        <?= Html::a('<span class="fa fa-camera"></span>' . Yii::t('hotels', 'Images'), ['images', 'id' => $model->id], ['class' => 'btn btn-app']) ?>
        <?= Html::a('<span class="fa fa-th"></span>' . Yii::t('partner_widget', 'Widgets'), ['widgets', 'id' => $model->id], [
                'class' => 'btn btn-app',
                'data-toggle' => 'popover',
                'data-trigger' => 'hover focus',
                'data-html' => 'true',
                'data-container' => "body",
                'data-placement' => "auto bottom",
                'data-content' => Yii::t('hotels','Install on your site widget for online booking'),
            ]) ?>
        <?= Html::a('<span class="fa fa-code"></span>' . Yii::t('hotels', 'Frame on site'), ['iframe', 'id' => $model->id], [
                'class' => 'btn btn-app',
                'data-toggle' => 'popover',
                'data-trigger' => 'hover focus',
                'data-html' => 'true',
                'data-container' => "body",
                'data-placement' => "auto bottom",
                'data-content' => Yii::t('hotels','Install on your site booking form'),
        ]) ?>
        <?= Html::a('<span class="fa fa-css3"></span>' . Yii::t('hotels', 'Custom CSS'), ['css', 'id' => $model->id], [
                'class' => 'btn btn-app',
                'data-toggle' => 'popover',
                'data-trigger' => 'hover focus',
                'data-html' => 'true',
                'data-container' => "body",
                'data-placement' => "auto bottom",
                'data-content' => Yii::t('hotels','Change your hotel page styles as you like'),
        ]) ?>
        <?php if (!$model->domain) {
                echo Html::a('<span class="fa fa-registered"></span>' . Yii::t('hotels', 'My domain'), ['domain-registration-request', 'id' => $model->id], [
                    'class' => 'btn btn-app bg-green',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'hover focus',
                    'data-html' => 'true',
                    'data-container' => "body",
                    'data-placement' => "auto bottom",
                    'data-content' => Yii::t('hotels','Place your hotel web page on it&acute;s own domain name, not on <i>{site}</i> subdomain', ['site' => Yii::$app->params['mainDomainShort']]),
                ]);
        } ?>
        <a class="btn btn-app bg-red" href="javascript:promptDelete();"><span class="fa fa-trash-o"></span><?= Yii::t('hotels', 'Delete') ?></a>

        <?php if (!$model->frozen): ?>
            <?= Html::a('<span class="fa fa-lock"></span>' . Yii::t('hotels', 'Freeze'), ['freeze', 'id' => $model->id], [
                'class' => 'btn btn-app bg-red',
                'data' => [
                    'confirm' => Yii::t('hotels', 'Are you sure you want to freeze this hotel?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?php if ($model->frozen): ?>
            <?= Html::a('<span class="fa fa-unlock"></span>' . Yii::t('hotels', 'Unfreeze'), ['unfreeze', 'id' => $model->id], [
                'class' => 'btn btn-app bg-red',
                'data' => [
                    'confirm' => Yii::t('hotels', 'Are you sure you want to unfreeze this hotel?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>

    </p>

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('hotels', 'Images') ?></h3>
                    <?php if ($model->images): ?>
                        <span class="badge pull-right"><?= count($model->images) ?></span>
                    <?php endif; ?>
                </div>
                <div class="box-body">
                    <?php if (!$model->images): ?>
                        <div class="alert alert-info alert-dismissable">
                            <h4>
                                <i class="icon fa fa-info"></i> <?= \Yii::t('hotels', 'No photos') ?>
                            </h4>
                            <?= Html::a(\Yii::t('hotels', 'Add hotel photos'), ['images', 'id' => $model->id]) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->images): ?>
                        <div id="hotel-images-carousel" class="carousel slide" data-ride="carousel">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
                                <?php foreach ($model->images as $i => $image): ?>
                                    <div class="item <?= $i == 0 ? "active" : '' ?>">
                                        <img src="<?= $image->getThumbUploadUrl('image', 'bigPreview') ?>" alt="">
                                        <a class="colorbox" href="<?= $image->getUploadUrl('image') ?>"
                                           rel="hotel-images"
                                           style="display:none;"></a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- Controls -->
                            <a class="left carousel-control" href="#hotel-images-carousel" role="button"
                               data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#hotel-images-carousel" role="button"
                               data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($model->images): ?>
                    <div class="box-footer text-center">
                        <?= Html::a(\Yii::t('hotels', 'Manage photos'), ['images', 'id' => $model->id]) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= \Yii::t('hotels', 'Information') ?></h3>
                </div>
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'category',
                            [
                                'attribute' => 'name',
                                'value' => '<a target="_blank" href="' . $model->local_url . '">' . $model->local_url . '</a>',
                                'format' => 'raw',
                            ],
                            'domain',
                            'title_' . $l,
                            'description_' . $l,
//                            'currency',
                            'address_' . $l,
                            'contact_email',
                            'contact_phone',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($rooms): ?>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('hotels', 'Rooms') ?></h3>
            </div>
            <div class="box-body">
                <?php foreach ($rooms as $index => $room): ?>
                    <?php /** @var \common\models\Room $room */ ?>
                    <div class="row rooms-list">
                        <div class="col-sm-3 col-md-2 col-lg-1">
                            <?php if ($room->images): ?>
                                <div id="room-images-carousel-<?= $room->id ?>" class="carousel slide"
                                     data-ride="carousel">
                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        <?php foreach ($room->images as $i => $image): ?>
                                            <div class="item <?= $i == 0 ? "active" : '' ?>">
                                                <img src="<?= $image->getThumbUploadUrl('image', 'preview') ?>" alt="">
                                                <a class="colorbox" href="<?= $image->getUploadUrl('image') ?>"
                                                   rel="hotel-images"
                                                   style="display:none;"></a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#room-images-carousel-<?= $room->id ?>"
                                       role="button"
                                       data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#room-images-carousel-<?= $room->id ?>"
                                       role="button"
                                       data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-9 col-md-10 col-lg-11 text-muted">
                            <?= Html::a($room->{'title_' . $l}, ['rooms', 'id' => $model->id, '#' => '/edit/' . $room->id]) ?>
                            <br/>
                            <?= \common\components\ListPriceType::getById($room->price_type)['name_' . $l] ?>
                            <br/>
                            <?= \Yii::t('rooms', 'Adults') ?>: <strong
                                class="label label-default"><?= $room->adults ?></strong>
                            &nbsp;
                            <?= \Yii::t('rooms', 'Children') ?>: <strong
                                class="label label-default"><?= $room->children ?></strong>
                            &nbsp;
                            <?= \Yii::t('rooms', 'Total') ?>: <strong
                                class="label label-default"><?= $room->total ?></strong>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($rooms): ?>
                <div class="box-footer text-center">
                    <?= \yii\helpers\Html::a(\Yii::t('hotels', 'Manage rooms'), ['rooms', 'id' => $model->id], []) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="remodal" id="modal">
        <button data-remodal-action="close" class="remodal-close"></button>
        <h1><?= Yii::t('hotels', 'Confirm action') ?></h1>
        <p>
            <?= Yii::t('hotels', 'The hotel will be permanently deleted. If you sure you want to continue type "delete".') ?>
        </p>
        <p><input id="submit_prompt"></p>
        <br>
        <button data-remodal-action="cancel" class="btn"><?= Yii::t('hotels', 'Cancel') ?></button>
        <button data-remodal-action="confirm" class="btn btn-success"><?= Yii::t('hotels', 'Ok') ?></button>
    </div>

</div>

