<?php

/* @var $this yii\web\View */
use common\models\Hotel;

/* @var $order common\models\Order */

//$lang = $order->lang;

/** @var \common\models\Currency $currency */
$currency = $order->hotel->currency;

/** @var Hotel $hotel */
$hotel = $order->hotel;

$td = "width: 50%; border-bottom: 1px dotted #999; padding: 5px;";

$td_label = "vertical-align: bottom; text-align:left; font-weight: bold; color: #333; border-right: 0px; padding-right: 2px;";

$td_value = "vertical-align: top; text-align:right; border-left: 0px; padding-left: 2px;";

$values = [
    \Yii::t('mails_order', 'Hotel', [], $local) => $order->hotel->{'title_' . $lang},
    \Yii::t('mails_order', 'Hotel information', [], $local) => $hotel->address."<br>".$hotel->contact_email."<br>".$hotel->contact_phone,
    \Yii::t('mails_order', 'Check-in date', [], $local) => (new \DateTime($order->dateFrom))->format(\Yii::t('mails_order', 'd/m/Y', [], $local)),
    \Yii::t('mails_order', 'Check-out date', [], $local) => (new \DateTime($order->dateTo))->format(\Yii::t('mails_order', 'd/m/Y', [], $local)),
    \Yii::t('mails_order', 'Order sum', [], $local) => $currency->getFormatted($order->sum, 'email'),
    \Yii::t('mails_order', 'Pay sum', [], $local) => $currency->getFormatted($order->pay_sum, 'email'),
    \Yii::t('mails_order', 'Status', [], $local) => \common\models\Order::getOrderStatusTitle($order->status),
    \Yii::t('mails_order', 'Contact information', [], $local) => \common\components\ListAddressType::getTitle($order->contact_address, $local) . ' ' .  \yii\helpers\Html::encode($order->contact_name) . ' ' . \yii\helpers\Html::encode($order->contact_surname) . "<br> <a href=\"mailto:" . \yii\helpers\Html::encode($order->contact_email). '">' . \yii\helpers\Html::encode($order->contact_email) . '</a>' . "<br/>" . \yii\helpers\Html::encode($order->contact_phone),

]

?>

<table style="border-collapse: collapse; width: 100%;">

    <?php foreach ($values as $label => $value): ?>
        <tr>
            <td style="<?= $td . $td_label ?>"><?=$label?></td>
            <td style="<?= $td . $td_value ?>"><?=$value?></td>
        </tr>
    <?php endforeach; ?>

</table>

<br>

<p style="color: #630; font-weight: bold; font-size: 0.9rem;"><?= \Yii::t('mails_order', 'Rooms', [], $local) ?>:</p>
<table style="border-collapse: collapse; width: 100%;">
    <?php foreach ($order->orderItems as $index => $item): ?>
        <?php /** @var \common\models\OrderItem $item */ ?>
        <tr>
            <td colspan="3" style="color: #333; font-weight: bold; font-size: 0.8rem;">
                <?= $index+1 ?>. <?= $item->room->{'title_' . $lang} ?>
            </td>
            <td style="vertical-align: middle; text-align: right;" rowspan="2">
                <?= \Yii::t('mails_order', 'Sum', [], $local) ?>:<br>
                <div style="font-size: 1rem; font-weight: bold;">
                    <?= $currency->getFormatted($item->sum, 'email') ?>
                </div>
            </td>
        </tr>
        <tr>
            <td><?= \Yii::t('mails_order', 'Adults: <b>{n}</b>', ['n' => $item->adults], $local) ?></td>
            <td> <?= \Yii::t('mails_order', 'Children 7-12 y.o.: <b>{n}</b>', ['n' => $item->children], $local) ?></td>
            <td><?= \Yii::t('mails_order', 'Children 0-6 y.o.: <b>{n}</b>', ['n' => $item->kids], $local) ?></td>
        </tr>

    <?php endforeach; ?>
</table>
