<?php

/* @var $this yii\web\View */
?>

<p><?= \Yii::t('mails_signup', 'Dear visitor!') ?>,</p>

<p><?= \Yii::t('mails_signup', 'You left registration request on partner.king-boo.com. To continue, please, follow the link below:') ?><br />
<a href="<?= $link ?>"><?= $link ?></a></p>

<p><?= \Yii::t('mails_signup', 'Please, use following data to login:') ?><br />
<?= \Yii::t('mails_signup', 'E-mail') ?>: <b><?= $email ?></b><br />
<?= \Yii::t('mails_signup', 'password') ?>: <b><?= $password ?></b></p>

<p><?= \Yii::t('mails_signup', 'Your personal manager') ?> <b><?= \Yii::t('mails_signup', 'Svetlana') ?></b>.
    <?= \Yii::t('mails_signup', 'You can communicate to her any time.') ?><br />
<?= \Yii::t('mails_signup', 'Contact phone') ?>: <b><?= \Yii::$app->params['phones.manager'] ?></b><br />
<?= \Yii::t('mails_signup', 'Contact email') ?>: <b><?= key(\Yii::$app->params['email.manager']) ?></b></p>

<?= \Yii::t('mails_signup', '<p>You can see all about King-Boo on <a href="{site}">website</a>.</p>', ['site' => \Yii::$app->params['mainProtocol'] . '://' . \Yii::$app->params['mainDomain']]) ?>

<p><?= \Yii::t('mails_signup', 'Best regards, team of king-boo.com') ?></p>
