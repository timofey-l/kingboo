<?php

namespace partner\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class RemodalAsset extends AssetBundle
{
    public $sourcePath = '@bower/remodal/dist';
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
    public $css = [
        'remodal.css',
        'remodal-default-theme.css',
    ];
    public $js = [
        'remodal.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
