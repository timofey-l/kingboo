<?php
use yii\helpers\Html;

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
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
