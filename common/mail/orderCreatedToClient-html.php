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
    <p style="color: #630; font-weight: bold; font-size: 1rem;"><?= \Yii::t('mails_order', 'Dear, {name}!', ['name' => $order->contact_name . ' ' . $order->contact_surname], $local) ?></p>

    <p><?= \Yii::t('mails_order', 'You made a order on the site <a href="http://king-boo.com">king-boo.com</a>.', [], $local) ?></p>

    <p><?= \Yii::t('mails_order', 'Order number:', ['n' => $order->number], $local) ?> <span style="font-family: Monaco, Menlo, Consolas, 'Courier New', monospace; font-weight: bold; padding: 3px; color: #D17C05;"><?= $order->number ?></span></p>

    <?php //оплата оп яндекс.кассе ?>
    <?php if (!$order->payment_via_bank_transfer && !$order->checkin_fullpay): ?>
        <p><?= \Yii::t('mails_order', 'The rooms are now booked, but the booking will be canceled if payment is not received within 24 hours.', []) ?></p>
        <p><?= \Yii::t('mails_order', 'To make a payment, please click the link below <br/><a href="{url}">Go to payment page</a>', ['url' => 'http://king-boo.com/payment/' . $order->payment_url], $local) ?></p>
    <?php endif; ?>

    <?php // оплата при заселении ?>
    <?php if ($order->checkin_fullpay): ?>
        <p><?= \Yii::t('mails_order', 'Booking is successfully complete. You need to make the full payment at check in  - {sum}.', ['sum' => $order->sumCurrency->getFormatted($order->sum)]) ?></p>
    <?php endif; ?>
    <p style="color: #630; font-weight: bold; font-size: 0.9rem;"><?= \Yii::t('mails_order', 'Order details') ?>:</p>

    <p><?= $this->render('_order-html', ['order' => $order, 'lang' => $lang, 'local' => $local]) ?></p>

    <br>
    <br>
    <br>

    <p style=" font-style: italic; "><?= \Yii::t('mails_order', 'We wish you a pleasant journey!<br>Team king-boo.com', [], $local) ?></p>
</div>
