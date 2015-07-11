<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */

$lang = $order->lang;
$translate_category = 'mails_order';

/** @var \common\models\Currency $currency */
$currency = $order->hotel->currency;

?>

<?= \Yii::t($translate_category, 'Check-in date', [], $lang) ?>: <?= (new \DateTime($order->dateFrom))->format(\Yii::t($translate_category, 'd.m.Y', [], $lang)) ?>
<?= \Yii::t($translate_category, 'Check-out date', [], $lang) ?>: <?= (new \DateTime($order->dateTo))->format(\Yii::t($translate_category, 'd.m.Y', [], $lang)) ?>
<?= \Yii::t($translate_category, 'Nights: {n}', ['n' => $order->nights], $lang) ?>
<?= \Yii::t($translate_category, 'Order sum', [], $lang) ?>: <?= $currency->getFormatted($order->sum) ?>
<?= \Yii::t($translate_category, 'Pay sum', [], $lang) ?>: <?= $currency->getFormatted($order->pay_sum) ?>

<?= \Yii::t($translate_category, 'Hotel', [], $lang) ?>: <?= $order->hotel->{'title_' . $lang} ?>
<?= \Yii::t($translate_category, 'Contact information', [], $lang) ?>:
    <?= \common\components\ListAddressType::getTitle($order->contact_address, $lang) ?> <?= \yii\helpers\Html::encode($order->contact_name) ?> <?= \yii\helpers\Html::encode($order->contact_surname) ?>
    <?= \yii\helpers\Html::encode($order->contact_email) ?>
    <?= \yii\helpers\Html::encode($order->contact_phone) ?>

<?= \Yii::t($translate_category, 'Numbers', [], $lang) ?>:<?php foreach ($order->orderItems as $index => $item): ?><?php /** @var \common\models\OrderItem $item */ ?>
<?= $index ?>. <?= $item->room->{'title_' . $lang} ?>
        <?= \Yii::t($translate_category, 'Sum', [], $lang) ?>: <?= $currency->getFormatted($item->sum) ?>
        <?= \Yii::t($translate_category, 'Adults: {n}', ['n' => $item->adults], $lang) ?>
        <?= \Yii::t($translate_category, 'Children 7-12 y.o.: {n}', ['n' => $item->children], $lang) ?>
        <?= \Yii::t($translate_category, 'Children 0-6 y.o.: {n}', ['n' => $item->kids], $lang) ?>
<?php endforeach; ?>
