<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>

    </style>
</head>
<body style="background: #eee; font-family: 'Helvetica Neue', 'Arial', sans-serif; font-size: 0.8rem;">
    <?php $this->beginBody() ?>
    <div style="background: #fff; max-width: 600px; margin: 0 auto; width: 100%;">
        <div style="background: #fff; height: 50px;border-bottom: 4px solid #f9980f;">
            <img src="http://www.king-boo.com/img/emails-logo.png" width="163" height="50" alt="king-boo.com"/>
        </div>
        <div style="padding: 1em 2em;">

            <?= $content ?>
        </div>


        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="font-size: 0.7rem; padding: 1em; background: #dee65c; text-align: center; color: #000;">
                    Copyright &copy; 2015 king-boo.com <?= \Yii::t('emails', 'All rights reserved.', [], ArrayHelper::getValue($this->params, 'local')) ?>
                    <br>
                    <?= \Yii::t('emails', 'This message was sent by king-boo.com service.', []) ?>
                </td>
            </tr>
        </table>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
