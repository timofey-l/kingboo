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
class FotoramaAsset extends AssetBundle
{
    public $sourcePath = '@bower/fotorama';
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
    public $css = [
        'fotorama.css',
    ];
    public $js = [
        'fotorama.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
