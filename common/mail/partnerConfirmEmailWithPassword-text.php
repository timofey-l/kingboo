<?php

/* @var $this yii\web\View */

?>

<?= \Yii::t('mails_signup', 'Dear visitor!') ?>,

<?= \Yii::t('mails_signup', 'You left registration request on partner.king-boo.com. To continue, please, follow the link below:') ?>

<?= $link ?>


<?= \Yii::t('mails_signup', 'Please, use following data to login:') ?>

<?= \Yii::t('mails_signup', 'E-mail') ?>: <?= $email ?>

<?= \Yii::t('mails_signup', 'password') ?>: <?= $password ?>


<?= \Yii::t('mails_signup', 'Your personal manager') ?> <?= \Yii::t('mails_signup', 'Svetlana') ?>. <?= \Yii::t('mails_signup', 'You can communicate to her any time.') ?>

<?= \Yii::t('mails_signup', 'Contact phone') ?>: <?= \Yii::$app->params['phones.manager'] ?>

<?= \Yii::t('mails_signup', 'Contact email') ?>: <?= key(\Yii::$app->params['email.manager']) ?>


<?= \Yii::t('mails_signup', 'You can see all about King-Boo on website {site}.', ['site' => \Yii::$app->params['mainProtocol'] . '://' . \Yii::$app->params['mainDomain']]) ?>


<?= \Yii::t('mails_signup', 'Best regards, team of king-boo.com') ?>
