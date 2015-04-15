<?php
namespace partner\assets;

use yii\web\AssetBundle;

class RoompricesManageAsset extends AssetBundle
{
//    public $sourcePath = '@partner/assets/web/';
    public $js = [
        'js/roomprices/langs.js',
        'js/roomprices/controllers.js',
        'js/roomprices/services.js',
        'js/roomprices/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'partner\assets\AngularAsset',
    ];
}

