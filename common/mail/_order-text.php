<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */

//$lang = $order->lang;

/** @var \common\models\Currency $currency */
$currency = $order->hotel->currency;

?>

<?= \Yii::t('mails_order', 'Check-in date', [], $local) ?>: <?= (new \DateTime($order->dateFrom))->format(\Yii::t('mails_order', 'd/m/Y', [], $local)) ?>
<?= \Yii::t('mails_order', 'Check-out date', [], $local) ?>: <?= (new \DateTime($order->dateTo))->format(\Yii::t('mails_order', 'd/m/Y', [], $local)) ?>
<?= \Yii::t('mails_order', 'Nights: {n}', ['n' => $order->nights], $local) ?>
<?= \Yii::t('mails_order', 'Order sum', [], $local) ?>: <?= $currency->getFormatted($order->sum) ?>
<?= \Yii::t('mails_order', 'Pay sum', [], $local) ?>: <?= $currency->getFormatted($order->pay_sum) ?>

<?= \Yii::t('mails_order', 'Hotel', [], $local) ?>: <?= $order->hotel->{'title_' . $lang} ?>
<?= \Yii::t('mails_order', 'Contact information', [], $local) ?>:
    <?= \common\components\ListAddressType::getTitle($order->contact_address, $local) ?> <?= \yii\helpers\Html::encode($order->contact_name) ?> <?= \yii\helpers\Html::encode($order->contact_surname) ?>
    <?= \yii\helpers\Html::encode($order->contact_email) ?>
    <?= \yii\helpers\Html::encode($order->contact_phone) ?>

<?= \Yii::t('mails_order', 'Rooms', [], $local) ?>:<?php foreach ($order->orderItems as $index => $item): ?><?php /** @var \common\models\OrderItem $item */ ?>

<?= $index + 1 ?>. <?= $item->room->{'title_' . $lang} ?>
        <?= \Yii::t('mails_order', 'Sum', [], $local) ?>: <?= $currency->getFormatted($item->sum) ?>
        <?= \Yii::t('mails_order', 'Adults: {n}', ['n' => $item->adults], $local) ?>
        <?= \Yii::t('mails_order', 'Children 7-12 y.o.: {n}', ['n' => $item->children], $local) ?>
        <?= \Yii::t('mails_order', 'Children 0-6 y.o.: {n}', ['n' => $item->kids], $local) ?>
<?php endforeach; ?>
