<?php
/* @var $this yii\web\View */
/* @var $model common\models\Hotel */
$l = \common\models\Lang::$current->url;
$directoryBower = Yii::$app->assetManager->getPublishedUrl('@bower');

$this->title = $model->{'title_' . $l};

$this->registerJsFile($directoryBower . '/colorbox/jquery.colorbox-min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($directoryBower . '/colorbox/i18n/jquery.colorbox-'.$l.'.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile($directoryBower . '/colorbox/example1/colorbox.css',[],'colorbox');

\frontend\assets\GalleryAsset::register($this);
$this->registerCssFile('/css/gallery.css', ['depends' => [\frontend\assets\GalleryAsset::className()]], 'gallery');

//Подключаем colorbox


$this->registerJs("
var changeSlider = function (swiper) {
            var slide = swiper.slides[swiper.activeIndex];
            var url = $(slide).data('big-preview');
            $('#hotelImagesBig .big-image-div').css({
                'background-image': 'url(' + url + ')'
            });
            $('#hotelImagesBig .big-image-div')[0].onclick = function() {
                $(slide).find('a').trigger('click');
            };

            setTimeout(function(){
                $('#hotelImagesBig .big-image-div').css({
                    opacity: 1
                });
            }, 100);
        };
var hotelImages = new Swiper ('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 'auto',
        centeredSlides: true,
        paginationClickable: true,
        slideToClickedSlide: true,
        spaceBetween: 10,
        onSlideChangeStart: function (swiper) {
            $('#hotelImagesBig .big-image-div').css({
                opacity: 0
            });
        },
        onSlideChangeEnd: changeSlider
});
changeSlider(hotelImages);
$('#hotel-images-container .swiper-button-prev').click(function(){
    hotelImages.slidePrev();
});
$('#hotel-images-container .swiper-button-next').click(function(){
    hotelImages.slideNext();
});

var resize = function(){
    var el = $('#hotelImagesBig');
    el.height( el.width()/1.7);
};

window.onresize = resize;
resize();
$('.swiper-slide a').colorbox();
", \yii\web\View::POS_READY, 'hotel_images');

?>
<h1><?= $model->{'title_' . $l} ?></h1>
<div class="hotel_stars">
    <?php foreach (range(1, 5) as $i): ?>
        <?php if ($i <= $model->category) : ?>
            &#x2605;
        <?php else: ?>
            &#x2606;
        <?php endif ?>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-sm-6 images-container">
        <!-- Slider main container -->
        <div class="big-image" id="hotelImagesBig">
            <div class="big-image-div"></div>
        </div>
        <div class="swiper-container" id="hotel-images-container">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <?php foreach ($model->images as $image): /* @var $image \common\models\RoomImage */ ?>
                    <div class="swiper-slide"
                         style="background-image: url('<?= $image->getThumbUploadUrl('image', 'preview') ?>');"
                        data-big-preview="<?= $image->getThumbUploadUrl('image', 'bigPreview') ?>">
                        <a href="<?= $image->getUploadUrl('image') ?>" rel="hotelImages"></a>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>

            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div>
    </div>
    <div class="col-sm-6 info-container">
        <p>
            <?= $model->{'description_' . $l} ?>
        </p>
    </div>
</div>

