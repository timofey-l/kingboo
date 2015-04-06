<?php
namespace partner\assets;

use yii\web\AssetBundle;

class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower/';
    public $js = [
        'angular/angular.min.js',
        'angular-mocks/angular-mocks.js',
        'angular-route/angular-route.min.js',
        'angular-resource/angular-resource.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}

