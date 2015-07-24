<?php

/* @var $this yii\web\View */

?>

<p><?= \Yii::t('mails_signup', 'Dear visitor!') ?></p>
<p><?= \Yii::t('mails_signup', 'You left registration request on partner.king-boo.com. To continue, please, follow the link below:') ?></p>
<p><a href="<?= $link ?>"><?= $link ?></a></p>

<p><?= \Yii::t('mails_signup', 'Best regards, team of king-boo.com') ?></p>