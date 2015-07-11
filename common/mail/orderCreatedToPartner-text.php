<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */
/* @var $lang string */

if (!isset($lang)) {
    $lang = $order->hotel->partner->lang;
}

?>
<?= \Yii::t('mails_order', 'Hello!', [], $lang) ?>
<?= \Yii::t('mails_order', 'Order #{n} has been made on site king-boo.com.', ['n' => $order->partner_number], $lang) ?>
<?= \Yii::t('mails_order', 'Order details') ?>:
<?= $this->render('_order-text', ['order' => $order, 'lang' => $lang]) ?>
<?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $lang) ?>