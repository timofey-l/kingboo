<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */
/* @var $lang string */
/* @var $local string */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

if (!isset($lang)) {
    $lang = $order->hotel->partner->lang;
}
?>
<div>
    <p><?= \Yii::t('mails_order', 'Dear, {name}!', ['name' => $order->contact_name . ' ' . $order->contact_surname], $local) ?></p>

    <p><?= \Yii::t('mails_order', 'Status of your order #{n} was changed to "{new_status}".', ['new_status' => \common\models\Order::getOrderStatusTitle($order->status), 'n' => $order->number], $local) ?></p>

    <p><?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $local) ?></p>

</div>