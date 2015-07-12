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
    <p><?= \Yii::t('mails_order', 'Hello!', [], $local) ?></p>

    <p><?= \Yii::t('mails_order', 'Status of order #{n} was changed to "{new_status}".', ['new_status' => \common\models\Order::getOrderStatusTitle($order->status), 'n' => $order->partner_number], $local) ?></p>

    <p><?= \Yii::t('mails_order', 'You can open order to manage it by <a href="{url}">clicking here</a>.', ['url' => \yii\helpers\Url::to('@partner_web') . '/orders/view?id=' . $order->id]) ?></p>

    <p><?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $local) ?></p>

</div>