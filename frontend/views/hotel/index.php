<?php
/* @var $this yii\web\View */
/* @var $model common\models\Hotel */
$l = \common\models\Lang::$current->url;

\frontend\assets\GalleryAsset::register($this);
$this->registerCssFile('/css/gallery.css', [], 'gallery');

?>
<h1><?= $model->{'title_'.$l} ?></h1>
<div class="hotel_stars">
    <?php foreach (range(1,5) as $i): ?>
        <?php if ($i <= $model->category) : ?>
            &#x2605;
        <?php else: ?>
            &#x2606;
        <?php endif ?>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-sm-6 images-container">
        <div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 600px; height: 300px;">
            <!-- Slides Container -->
            <?php foreach($model->images as $image): /* @var $image \common\models\RoomImage */ ?>
            <div u="slides" style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: 600px; height: 300px;">
                <div><img u="image" src="<?= $image->getUploadUrl('image') ?>" /></div>
                <div><img u="image" src="<?= $image->getThumbUploadUrl('image', 'preview') ?>" /></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-sm-6 info-container">
        <p>
            <?= $model->{'description_' . $l} ?>
        </p>
    </div>
</div>