<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */

$lang = $order->lang;

?>
<?= \Yii::t('mails_order', 'Hello!', [], $lang) ?>
<?= \Yii::t('mails_order', 'Order #{n} has been made on site king-boo.com.', ['n' => $order->partner_number], $lang) ?>
<?= \Yii::t('mails_order', 'Order details') ?>:
<?= $this->render('_order-text', ['order' => $order]) ?>
