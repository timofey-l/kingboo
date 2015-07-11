<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */

//$lang = $order->lang;

/** @var \common\models\Currency $currency */
$currency = $order->hotel->currency;

$td = "border: 0px solid #eeeeee; padding: 5px;";

$td_label = "vertical-align: top; text-align:right; color: #666; border-right: 0px; padding-right: 2px;";

$td_value = "vertical-align: top; text-align:left; font-weight: bold; border-left: 0px; padding-left: 2px;";

?>

<table style="border-collapse: collapse;">
    <tr>
        <td style="<?= $td . $td_label ?>"
            style="<?= $td ?>"><?= \Yii::t('mails_order', 'Check-in date', [], $lang) ?></td>
        <td style="<?= $td . $td_value ?>"
            style="<?= $td ?>"><?= (new \DateTime($order->dateFrom))->format(\Yii::t('mails_order', 'd/m/Y', [], $lang)) ?></td>
        <td style="<?= $td ?>" style="<?= $td ?>" rowspan="2">
            <?= \Yii::t('mails_order', 'Nights: {n}', ['n' => $order->nights], $lang) ?>
        </td>
        <td style="<?= $td . $td_label ?>">
            <?= \Yii::t('mails_order', 'Order sum', [], $lang) ?>
        </td>
        <td style="<?= $td . $td_value ?>">
            <?= $currency->getFormatted($order->sum) ?>
        </td>
    </tr>
    <tr>
        <td style="<?= $td . $td_label ?>"><?= \Yii::t('mails_order', 'Check-out date', [], $lang) ?></td>
        <td style="<?= $td . $td_value ?>"><?= (new \DateTime($order->dateTo))->format(\Yii::t('mails_order', 'd/m/Y', [], $lang)) ?></td>
        <td style="<?= $td . $td_label ?>">
            <?= \Yii::t('mails_order', 'Pay sum', [], $lang) ?>
        </td>
        <td style="<?= $td . $td_value ?>">
            <?= $currency->getFormatted($order->pay_sum) ?>
        </td>
    </tr>
    <tr>
        <td style="<?= $td . $td_label ?>">
            <?= \Yii::t('mails_order', 'Status', [], $lang) ?>
        </td>
        <td style="<?= $td . $td_value ?>" colspan="4">
            <?= \common\models\Order::getOrderStatusTitle($order->status) ?>
        </td>
    </tr>
    <tr>
        <td style="<?= $td . $td_label ?>">
            <?= \Yii::t('mails_order', 'Hotel', [], $lang) ?>
        </td>
        <td style="<?= $td . $td_value ?>" colspan="4">
            <?= $order->hotel->{'title_' . $lang} ?>
        </td>
    </tr>
    <tr>
        <td style="<?= $td . $td_label ?>">
            <?= \Yii::t('mails_order', 'Contact information', [], $lang) ?>
        </td>
        <td style="<?= $td . $td_value ?>" colspan="4">
            <?= \common\components\ListAddressType::getTitle($order->contact_address, $lang) ?>
            <?= \yii\helpers\Html::encode($order->contact_name) ?> <?= \yii\helpers\Html::encode($order->contact_surname) ?>
            <br/>
            <a href="mailto:<?= \yii\helpers\Html::encode($order->contact_email) ?>"><?= \yii\helpers\Html::encode($order->contact_email) ?></a>
            <br/>
            <?= \yii\helpers\Html::encode($order->contact_phone) ?>
        </td>
    </tr>
</table>
<?= \Yii::t('mails_order', 'Rooms', [], $lang) ?>:
<table style="border-collapse: collapse;">
    <?php foreach ($order->orderItems as $index => $item): ?>
        <?php /** @var \common\models\OrderItem $item */ ?>
        <tr>
            <td style="<?= $td.";font-size: 150%; " ?>" rowspan="2"><?= $index+1 ?></td>
            <td style="<?= $td ?>"><?= $item->room->{'title_' . $lang} ?></td>
            <td style="<?= $td . $td_label ?>"><?= \Yii::t('mails_order', 'Sum', [], $lang) ?></td>
            <td style="<?= $td . $td_value ?>"><?= $currency->getFormatted($item->sum) ?></td>
        </tr>
        <tr>
            <td style="<?= $td . $td_label . "text-align:left;" ?>" colspan="3">
                <?= \Yii::t('mails_order', 'Adults: <b>{n}</b>', ['n' => $item->adults], $lang) ?>
                &nbsp;&nbsp;&nbsp;
                <?= \Yii::t('mails_order', 'Children 7-12 y.o.: <b>{n}</b>', ['n' => $item->children], $lang) ?>
                &nbsp;&nbsp;&nbsp;
                <?= \Yii::t('mails_order', 'Children 0-6 y.o.: <b>{n}</b>', ['n' => $item->kids], $lang) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
