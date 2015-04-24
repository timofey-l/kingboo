<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GalleryAsset extends AssetBundle
{
    public $sourcePath = '@bower/';
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        '/jssor-slider/js/jssor.slider.mini.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
