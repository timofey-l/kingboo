<?php
namespace partner\assets;

use yii\web\AssetBundle;

class HotelImagesAsset extends AssetBundle
{
//    public $sourcePath = '@partner/assets/web/';
    public $js = [
        'js/hotelimages/langs.js',
        'js/hotelimages/controllers.js',
        'js/hotelimages/services.js',
        'js/hotelimages/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'partner\assets\AngularAsset',
        'partner\assets\RemodalAsset',
    ];
}

