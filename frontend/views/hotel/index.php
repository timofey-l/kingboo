<?php
/* @var $this yii\web\View */
use frontend\assets\FotoramaAsset;
use yii\bootstrap\BootstrapAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $model common\models\Hotel */
/* @var $bookParams \frontend\models\BookingParams */
$l = \common\models\Lang::$current->url;
$assetManager = Yii::$app->assetManager;
//$directoryBower = $assetManager->getPublishedUrl('@bower');

$this->title = $model->{'title_' . $l};
$this->params['hotel'] = $model;

$this->params['embedded'] = $embedded;
$this->params['no_desc'] = $no_desc;

$this->registerCss($model->css, [
    'depends' => BootstrapAsset::className(),
]);

FotoramaAsset::register($this);

// underscore.js
$this->registerJsFile($assetManager->publish('@bower/underscore')[1] . '/underscore-min.js');

//Подключаем colorbox
$colorbox = $assetManager->publish('@bower/colorbox')[1];
$this->registerJsFile($colorbox . '/jquery.colorbox-min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($colorbox . '/i18n/jquery.colorbox-' . ($l == 'en' ? 'uk' : $l) . '.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile($colorbox . '/example1/colorbox.css', [], 'colorbox');

//Font Awesome
$this->registerCssFile($assetManager->publish('@bower/fontawesome')[1] . '/css/font-awesome.min.css', [], 'font-awesome');

// Подключаем библиотеку moment.js
$this->registerJsFile($assetManager->publish('@bower/moment/min')[1] . '/moment-with-locales.min.js');

//// Галлерея
//\frontend\assets\GalleryAsset::register($this);
//$this->registerCssFile('/css/gallery.css', ['depends' => [\frontend\assets\GalleryAsset::className()]], 'gallery');

// angular-sanitize
$ang_sanitize = \Yii::$app->assetManager->publish('@vendor/bower/angular-sanitize');
$assetOptions = ['depends' => [\partner\assets\AngularAsset::className()]];
$this->registerJsFile($ang_sanitize[1] . '/angular-sanitize.js', $assetOptions);

// Приложение angular
$this->registerJsFile('/js/search.js', ['depends' => [\frontend\assets\GalleryAsset::className()]]);
$this->registerJsFile('/js/search-app.js', ['depends' => [\partner\assets\AngularAsset::className()]]);

// Плагин datepicker
$datepicker = $assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/datepicker')[1];
$this->registerJsFile($datepicker . '/bootstrap-datepicker.js', ['depends' => [\yii\bootstrap\BootstrapAsset::className(), \yii\bootstrap\BootstrapPluginAsset::className()]]);
$this->registerJsFile($datepicker . '/locales/bootstrap-datepicker.' . $l . '.js', ['depends' => [\yii\bootstrap\BootstrapAsset::className(), \yii\bootstrap\BootstrapPluginAsset::className()]]);
$this->registerCssFile($datepicker . '/datepicker3.css', ['depends' => [\frontend\assets\GalleryAsset::className()]]);

// custom scrolbars
$customSB = $assetManager->publish('@bower/malihu-custom-scrollbar-plugin')[1];
$this->registerJsFile($customSB . '/jquery.mCustomScrollbar.concat.min.js', ['depends' => [BootstrapAsset::className()]]);
$this->registerCssFile($customSB . '/jquery.mCustomScrollbar.min.css');

// сворачивание описания отеля
$js = <<<Javascript
//console.log($(".collapsable"));
$(".collapsable").mCustomScrollbar({
    scrollButtons:{enable:true},
    theme:"dark-3",
    callbacks: {
        onOverflowY:function(){
            $('.shadow-bottom').css('opacity', 1);
        },
        whileScrolling: function() {
            if (this.mcs.topPct == 0) {
                if ($('.shadow-top').css('opacity') != 0) {
                    $('.shadow-top').css('opacity', 0);
                }
            }
            if (this.mcs.topPct == 100) {
                if ($('.shadow-bottom').css('opacity') != 0) {
                    $('.shadow-bottom').css('opacity', 0);
                }
            }
            if (this.mcs.topPct > 0 && this.mcs.topPct < 100) {
                if ($('.shadow-top').css('opacity') == 0) {
                    $('.shadow-top').css('opacity', 1);
                }
                if ($('.shadow-bottom').css('opacity') == 0) {
                    $('.shadow-bottom').css('opacity', 1);
                }
            }
        }
    }
    //scrollbarPosition:"outside"
});
Javascript;

$this->registerJs($js);

// проверяем возможность забронировать
if (!$this->context->checkBookingPossibility($model)) {
    $this->registerJs("const BOOKING_AVAILIBLE = false;", View::POS_HEAD);
} else {
    $this->registerJs("const BOOKING_AVAILIBLE = true;", View::POS_HEAD);
}
?>
<div class="device-xs visible-xs"></div>
<div class="device-sm visible-sm"></div>
<div class="device-md visible-md"></div>
<div class="device-lg visible-lg"></div>

<script>
    var dateFrom = "<?= $bookParams->dateFrom ?>";
    var dateTo = "<?= $bookParams->dateTo ?>";
    var adults = <?= $bookParams->adults ?>;
    var children = <?= $bookParams->children ?>;
    var kids = <?= $bookParams->kids ?>;
    var searchMode = <?= $search ?>;
</script>

<div class="<?= $no_desc == 1 ? 'hidden' : '' ?>">
    <div class="row">
        <div class="col-sm-6">
            <h1><?= $model->{'title_' . $l} ?></h1>
            <?php if ($model->category > 0) : ?>
            <div class="hotel_stars">
                <?php foreach (range(1, 5) as $i): ?>
                    <?php if ($i <= $model->category) : ?>
                        <i class="glyphicon glyphicon-star"></i>
                    <?php else: ?>
                        <i class="glyphicon glyphicon-star-empty"></i>
                    <?php endif ?>
                <?php endforeach; ?>
            </div>
            <?php endif ?>
        </div>
        <div class="col-sm-6 text-right">
            <br>
            <span class="address"><?= Html::encode($model->property('address')) ?></span>
            <br>
            <span class="phone"><?= Html::encode($model->contact_phone) ?></span>
            <br>
            <span class="email"><?= Html::mailto($model->contact_email) ?></span>
            <br>
        </div>
    </div>

    <div class="row">
        <?php if (count($model->images)): ?>
        <div class="col-md-6 col-sm-12 images-container">

            <div class="fotorama" data-nav="thumbs" data-allowfullscreen="true" data-fit="cover" data-thumbfit="true" data-thumbwidth="114" data-thumbheight="64" data-width="100%">
                <?php foreach ($model->images as $image): /* @var $image \common\models\RoomImage */ ?>
                    <a href="<?= $image->getUploadUrl('image') ?>">
                        <img src="<?= $image->getThumbUploadUrl('image', 'preview') ?>" alt="">
                    </a>
                <?php endforeach; ?>
            </div>


        </div>
        <?php endif; ?>
        <div class="col-md-6 col-sm-12 info-container">
            <div class="collapsable">
                <ul class="hotel-facilities row">
                    <?php foreach ($model->facilities as $f): ?>
                        <li class="col-xs-6 col-sm-3 col-md-6">
                            <?= $f->{'name_' . $l} ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="info-description">
                    <?= str_replace("\n", "<br>", Html::encode($model->{'description_' . $l})) ?>
                </div>
            </div>
            <div class="shadow-top" style="opacity: 0;"></div>
            <div class="shadow-bottom"></div>
        </div>
    </div>
</div>


<div ng-app="roomsSearch" ng-controller="searchCtrl" ng-init="search.hotelId = <?= $model->id ?>;" style="display: none;">
    <h2 id="search"><?= Yii::t('frontend', 'Search vacant rooms for booking') ?></h2>
    <div class="row well">
        <div class="input-daterange" id="datepicker">
            <div class="form-group col-md-2 col-sm-6">
                <label for="dateFrom"><?= Yii::t('frontend', 'Arrival date') ?></label>
                <input id="dateFrom" type="text" class="form-control" name="dateFrom"/>
            </div>

            <div class="form-group col-md-2 col-sm-6">
                <label for="dateTo"><?= Yii::t('frontend', 'Departure date') ?></label>
                <input id="dateTo" type="text" class="form-control" name="dateTo"/>
            </div>
        </div>
        <div class="form-group col-md-2 col-xs-6">
            <label for="adults"><?= Yii::t('frontend', 'Adults') ?></label>

            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-warning" type="button" ng-click="search.adults = search.adults - 1">
                        <i class="glyphicon glyphicon-minus"></i>
                    </button>
                </span>
                <input ng-model="search.adults" type="text" class="form-control" name="adults" id="adults">
                <span class="input-group-btn">
                    <button class="btn btn-warning" type="button" ng-click="search.adults = search.adults + 1">
                        <i class="glyphicon glyphicon-plus"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="form-group col-md-2 col-xs-6">
            <label for="children"><?= Yii::t('frontend', 'Children') ?></label>

            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-warning" type="button" ng-click="search.children = search.children - 1">
                        <i class="glyphicon glyphicon-minus"></i>
                    </button>
                </span>
                <input ng-model="search.children" type="text" class="form-control" name="children" id="children">
                <span class="input-group-btn">
                    <button class="btn btn-warning" type="button" ng-click="search.children = search.children + 1">
                        <i class="glyphicon glyphicon-plus"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="form-group col-md-2 col-xs-6">
            <label for="kids"><?= Yii::t('frontend', 'Kids') ?></label>

            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-warning" type="button" ng-click="search.kids = search.kids - 1">
                        <i class="glyphicon glyphicon-minus"></i>
                    </button>
                </span>
                <input ng-model="search.kids" type="text" class="form-control" name="kids" id="kids">
                <span class="input-group-btn">
                    <button class="btn btn-warning" type="button" ng-click="search.kids = search.kids + 1">
                        <i class="glyphicon glyphicon-plus"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="form-group col-md-2 col-xs-6">
            <label for="">&nbsp;</label>
            <br/>
            <button class="btn btn-warning" ng-click="find()"><?= Yii::t('frontend', 'Find') ?></button>
        </div>
        <div class="form-group col-xs-12 col-sm-10">
            <?= Yii::t('frontend', 'Use search form to book a room on disirable dates.') ?>
            <?= Yii::t('frontend', 'To watch information about all hotel rooms push the "All rooms" button.') ?>
        </div>
        <div class="form-group col-xs-12 col-sm-2">
            <button class="btn btn-warning" ng-disabled="!searchMode" ng-click="getRooms()"><?= Yii::t('frontend', 'All rooms') ?></button>
        </div>
    </div>

    <div class="alert alert-warning" role="alert" ng-show="!loading && searchMode && (!results || results.length === 0)">
        <?= \Yii::t('frontend', '<b>No rooms available for this period.</b> Please, select other days.');?>
    </div>
    <div class="row result-item well" ng-repeat="r in results">
        <div class="col-md-3" ng-show="r.images.length > 0">
            <div class="gallery thumbnail">
                <div class="swiper-container" id="{{'hotel-room-' + r.id}}" data-room-id="{{ r.id }}">
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        <a class="swiper-slide"
                           ng-style="{'background-image':'url('+i.preview+')'}"
                           href="{{ i.image }}"
                           rel="{{ 'hotelImages' + r.id }}"
                           ng-repeat="i in r.images">
                        </a>
                    </div>
                    <div class="swiper-pagination"></div>

                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>

                </div>
            </div>
        </div>
        <div class="col-md-9 info" ng-if="!searchMode">
            <div class="row">
                <div class="title">{{r['title_' + LANG]}}</div>
            </div>
            <div class="row">
                <div class="description">{{r['description_' + LANG]}}</div>
            </div>
            <div class="room-facilities row">
                    <span class="label label-warning" ng-repeat="f in r.facilities">
                        {{f['name_' + LANG]}}
                    </span>
            </div>
        </div>
        <div class="col-md-7 info" ng-if="searchMode">
            <div class="row">
                <div class="title">{{r['title_' + LANG]}}</div>
            </div>
            <div class="row">
                <div class="description">{{r['description_' + LANG]}}</div>
            </div>
            <div class="room-facilities row">
			        <span class="label label-warning" ng-repeat="f in r.facilities">
				        {{f['name_' + LANG]}}
			        </span>
            </div>
        </div>
        <div class="col-md-2 price" ng-if="searchMode">
            <span ng-bind-html="pFormat(r)"></span>
            <br/>
            <br/>

            <div class="btn btn-success" ng-disabled="window.BOOKING_AVAILIBLE === false" ng-click="goBooking(r)"><?= Yii::t('frontend', 'Booking') ?></div>
        </div>
    </div>
</div>

<?php if($model->widgetcall_enabled): ?>
    <?= $model->widgetcall_text ?>
<?php endif ?>
