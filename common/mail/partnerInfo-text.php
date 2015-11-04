<?php

/* @var $this yii\web\View */

foreach ($message as $k=>$m) {
    $message[$k] = str_replace(['<br>', '<br />'], "\n", $m);
    $message[$k] = strip_tags($m);
}
?>

<?= \Yii::t('emails', 'Hello', [], $locale) ?>!

<?php foreach ($message as $p) : ?>
<?= $p ?>

<?php endforeach; ?>
<?= \Yii::t('mails_signup', 'Best regards, team of king-boo.com', [], $locale) ?>
