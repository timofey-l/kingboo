<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */

//$lang = $order->lang;

/** @var \common\models\Currency $currency */
$currency = $order->hotel->currency;

?>

<?= \Yii::t('mails_order', 'Check-in date', [], $lang) ?>: <?= (new \DateTime($order->dateFrom))->format(\Yii::t('mails_order', 'd/m/Y', [], $lang)) ?>
<?= \Yii::t('mails_order', 'Check-out date', [], $lang) ?>: <?= (new \DateTime($order->dateTo))->format(\Yii::t('mails_order', 'd/m/Y', [], $lang)) ?>
<?= \Yii::t('mails_order', 'Nights: {n}', ['n' => $order->nights], $lang) ?>
<?= \Yii::t('mails_order', 'Order sum', [], $lang) ?>: <?= $currency->getFormatted($order->sum) ?>
<?= \Yii::t('mails_order', 'Pay sum', [], $lang) ?>: <?= $currency->getFormatted($order->pay_sum) ?>

<?= \Yii::t('mails_order', 'Hotel', [], $lang) ?>: <?= $order->hotel->{'title_' . $lang} ?>
<?= \Yii::t('mails_order', 'Contact information', [], $lang) ?>:
    <?= \common\components\ListAddressType::getTitle($order->contact_address, $lang) ?> <?= \yii\helpers\Html::encode($order->contact_name) ?> <?= \yii\helpers\Html::encode($order->contact_surname) ?>
    <?= \yii\helpers\Html::encode($order->contact_email) ?>
    <?= \yii\helpers\Html::encode($order->contact_phone) ?>

<?= \Yii::t('mails_order', 'Rooms', [], $lang) ?>:<?php foreach ($order->orderItems as $index => $item): ?><?php /** @var \common\models\OrderItem $item */ ?>

<?= $index + 1 ?>. <?= $item->room->{'title_' . $lang} ?>
        <?= \Yii::t('mails_order', 'Sum', [], $lang) ?>: <?= $currency->getFormatted($item->sum) ?>
        <?= \Yii::t('mails_order', 'Adults: {n}', ['n' => $item->adults], $lang) ?>
        <?= \Yii::t('mails_order', 'Children 7-12 y.o.: {n}', ['n' => $item->children], $lang) ?>
        <?= \Yii::t('mails_order', 'Children 0-6 y.o.: {n}', ['n' => $item->kids], $lang) ?>
<?php endforeach; ?>
