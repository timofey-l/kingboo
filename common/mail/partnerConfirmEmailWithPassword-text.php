<?php

/* @var $this yii\web\View */

?>

<?= \Yii::t('mails_signup', 'Dear visitor!') ?>,
<?= \Yii::t('mails_signup', 'You left registration request on partner.king-boo.com. To continue, please, follow the link below:') ?>
<?= $link ?>
<?= \Yii::t('mails_signup', 'Please, use following data to login:') ?>
<?= \Yii::t('mails_signup', 'email: {email}', ['email' => $email]) ?>
<?= \Yii::t('mails_signup', 'password: {password}', ['password' => $password]) ?>

<?= \Yii::t('mails_signup', 'Best regards, team of king-boo.com') ?>