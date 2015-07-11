<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */
/* @var $lang string */

if (!isset($lang)) {
    $lang = $order->lang;
}

?>

<div>
    <p><?= \Yii::t('mails_order', 'Hello, {name}!', ['name' => $order->contact_name . ' ' . $order->contact_surname], $lang) ?></p>
    <p><?= \Yii::t('mails_order', 'You made a order on the site <a href="http://king-boo.com">king-boo.com</a>.', [], $lang) ?></p>
    <p><?= \Yii::t('mails_order', 'Order number: {n}', ['n' => $order->number], $lang) ?></p>
    <p><?= \Yii::t('mails_order', 'The rooms are now booked, but the booking will be canceled if payment is not received within 24 hours.', []) ?></p>
    <p><?= \Yii::t('mails_order', 'To make a payment, please click the link below <br/><a href="{url}">{url}</a>', ['url' => 'http://king-boo.com/payment/'.$order->payment_url], $lang) ?></p>
    <p><?= \Yii::t('mails_order', 'Order details') ?>:</p>
    <p><?= $this->render('_order-html', ['order' => $order]) ?></p>
    <p></p>
    <p><?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $lang) ?></p>
</div>
