<?php
use yii\helpers\Html;
$assetManager = \Yii::$app->assetManager;

/* @var $this \yii\web\View */
/* @var $content string */
if (in_array(Yii::$app->controller->action->id, ['login', 'error', 'signup', 'request-password-reset', 'reset-password'])) {
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    partner\assets\AppAsset::register($this);

    $adminlte = dmstr\web\AdminLteAsset::register($this);
    $this->params['adminlte'] = $adminlte->baseUrl;
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    $directoryAsset = $adminlte->baseUrl;

    $bootstrap_js = $assetManager->publish('@bower/bootstrap/js')[1];
    $this->registerJsFile($bootstrap_js . "/tooltip.js", ['depends' => [dmstr\web\AdminLteAsset::className()]]);
    $this->registerJsFile($bootstrap_js . "/popover.js", ['depends' => [dmstr\web\AdminLteAsset::className()]]);
    $this->registerJsFile('/js/common-scripts.js', ['depends' => [yii\bootstrap\BootstrapAsset::className()]]);
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
	    <script>
		    var LANG = '<?= \common\models\Lang::$current->url ?>';
	    </script>
    </head>
    <body class="skin-blue-light">
    <?php $this->beginBody() ?>
    <div class="wrapper">


        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <div class="wrapper row-offcanvas row-offcanvas-left">

            <?= $this->render(
                'left.php',
                ['directoryAsset' => $directoryAsset]
            )
            ?>

            <?= $this->render(
                'content.php',
                ['content' => $content, 'directoryAsset' => $directoryAsset]
            ) ?>

        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
