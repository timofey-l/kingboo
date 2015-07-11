<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */

$lang = $order->lang;

?>

<div>
    <p><?= \Yii::t('mails_order', 'Hello!', [], $lang) ?></p>
    <p><?= \Yii::t('mails_order', 'Order #{n} has been made on site <a href="http://king-boo.com">king-boo.com</a>.', ['n' => $order->number], $lang) ?></p>
    <p><?= \Yii::t('mails_order', 'Order details^') ?></p>
    <p><?= $this->render('_order-html', ['order' => $order]) ?></p>
</div>