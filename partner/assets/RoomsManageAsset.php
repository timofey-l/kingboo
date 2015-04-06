<?php
namespace partner\assets;

use yii\web\AssetBundle;

class RoomsManageAsset extends AssetBundle
{
//    public $sourcePath = '@partner/assets/web/';
    public $js = [
        'js/rooms/langs.js',
        'js/rooms/controllers.js',
        'js/rooms/services.js',
        'js/rooms/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'partner\assets\AngularAsset',
    ];
}
