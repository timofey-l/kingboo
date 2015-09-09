<?php
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use rmrevin\yii\fontawesome\AssetBundle;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\base\View;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapThemeAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

//register theme assets

JqueryAsset::register($this);

AssetBundle::register($this);

BootstrapAsset::register($this);
//BootstrapThemeAsset::register($this);

$assetOptions = [
    'depends' => [
        JqueryAsset::className(),
        AssetBundle::className(),
    ],
];

$this->registerCssFile($this->theme->getUrl('/css/styles.css'), $assetOptions);
$this->registerCssFile($this->theme->getUrl('/css/custom.css'), $assetOptions);
$this->registerJsFile($this->theme->getUrl('/js/init.js'), $assetOptions);

$this->registerJsFile('/js/langs.js');
$this->registerJsFile('/js/format.js');


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script>
        var LANG = '<?= \common\models\Lang::$current->url ?>';
    </script>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<div class="layer-olive-row-content"></div>
<div id="container" class="content">
    <?= $this->render('header') ?>
    <div id="content" class="container">
        <?= $content ?>
    </div>
</div>

<?= $this->render('footer'); ?>
<?php $this->endBody() ?></body></html><?php $this->endPage() ?>
