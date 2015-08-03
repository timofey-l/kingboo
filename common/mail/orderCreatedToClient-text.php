<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */
/* @var $lang string */

if (!isset($lang)) {
    $lang = $order->lang;
}
?>
<?= \Yii::t('mails_order', 'Hello, {name}!', ['name' => $order->contact_name . ' ' . $order->contact_surname], $local) ?>

<?= \Yii::t('mails_order', 'You made a order on the site <a href="https://king-boo.com">king-boo.com</a>.', [], $local) ?>

<?= \Yii::t('mails_order', 'Order number: {n}', ['n' => $order->number], $local) ?>

<?php //оплата оп яндекс.кассе ?>
<?php if (!$order->payment_via_bank_transfer && !$order->checkin_fullpay): ?>
    <?= \Yii::t('mails_order', 'The rooms are now booked, but the booking will be canceled if payment is not received within 24 hours.', []) ?>

    <?= \Yii::t('mails_order', 'To make a payment, please click the link below <br/><a href="{url}">{url}</a>', ['url' => 'https://king-boo.com/payment/' . $order->payment_url], $local) ?>

<?php endif; ?>

<?php // оплата при заселении ?>
<?php if ($order->checkin_fullpay): ?>
    <?= \Yii::t('mails_order', 'Booking is successfully complete. You need to make the full payment at check in  - {sum}.', ['sum' => $currency->getFormatted($order->sum)]) ?>
<?php endif; ?>
<?= \Yii::t('mails_order', 'Order details') ?>:
<?= $this->render('_order-html', ['order' => $order, 'lang' => $lang, 'local' => $local]) ?>

<?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $local) ?>