<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HotelsBootstrapAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/hotelsBootstrapTheme';
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/bootstrap-theme.min.css',
    ];
    public $js = [
        'js/bootstrap.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];
}
