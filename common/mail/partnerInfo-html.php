<?php

/* @var $this yii\web\View */

?>

<p><?= \Yii::t('emails', 'Hello', [], $locale) ?>!</p>

<?php foreach ($message as $p) : ?>
<p><?= $p ?></p>
<?php endforeach; ?>

<p><?= \Yii::t('mails_signup', 'Best regards, team of king-boo.com', [], $locale) ?></p>