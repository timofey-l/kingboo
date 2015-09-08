<?php

/* @var $this yii\web\View */
use common\models\Currency;

/* @var $order common\models\Order */
/* @var $lang string */

if (!isset($lang)) {
    $lang = $order->lang;
}

/** @var Currency $currency */
$currency = $order->hotel->currency;

?>
<div>
    <p><?= \Yii::t('mails_order', 'Dear, {name}!', ['name' => $order->contact_name . ' ' . $order->contact_surname], $local) ?></p>

    <p><?= \Yii::t('mails_order', 'You made a order on the site <a href="http://king-boo.com">king-boo.com</a>.', [], $local) ?></p>

    <p><?= \Yii::t('mails_order', 'Order number: {n}', ['n' => $order->number], $local) ?></p>

    <?php //оплата оп яндекс.кассе ?>
    <?php if (!$order->payment_via_bank_transfer && !$order->checkin_fullpay): ?>
        <p><?= \Yii::t('mails_order', 'The rooms are now booked, but the booking will be canceled if payment is not received within 24 hours.', []) ?></p>
        <p><?= \Yii::t('mails_order', 'To make a payment, please click the link below <br/><a href="{url}">{url}</a>', ['url' => 'http://king-boo.com/payment/' . $order->payment_url], $local) ?></p>
    <?php endif; ?>

    <?php // оплата при заселении ?>
    <?php if ($order->checkin_fullpay): ?>
        <p><?= \Yii::t('mails_order', 'Booking is successfully complete. You need to make the full payment at check in  - {sum}.', ['sum' => $order->sumCurrency->getFormatted($order->sum)]) ?></p>
    <?php endif; ?>
    <p><?= \Yii::t('mails_order', 'Order details') ?>:</p>

    <p><?= $this->render('_order-html', ['order' => $order, 'lang' => $lang, 'local' => $local]) ?></p>

    <p></p>

    <p><?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $local) ?></p>
</div>
