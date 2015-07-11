<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */

$lang = $order->lang;
$translate_category = 'mails_order';

/** @var \common\models\Currency $currency */
$currency = $order->hotel->currency;

$this->registerCss("
    .orderTable {

    }
    .orderTable td {
        padding: 0.2em;
        border: 1px solid #cccccc;
    }


", [], 'table')

?>

<table class="orderTable">
    <tr>
        <td colspan="2"><?= \Yii::t($translate_category, 'Check-in date', [], $lang) ?></td>
        <td><?= (new \DateTime($order->dateFrom))->format(\Yii::t($translate_category,'d.m.Y', [], $lang)) ?></td>
        <td rowspan="2">
            (<?= \Yii::t($translate_category, 'Nights: {n}', ['n' => $order->nights], $lang) ?>)
        </td>
        <td>
            <?= \Yii::t($translate_category, 'Order sum', [], $lang) ?>
        </td>
        <td>
            <?= $currency->getFormatted($order->sum) ?>
        </td>
    </tr>
    <tr>
        <td colspan="2"><?= \Yii::t($translate_category, 'Check-out date', [], $lang) ?></td>
        <td><?= (new \DateTime($order->dateTo))->format(\Yii::t($translate_category,'d.m.Y', [], $lang)) ?></td>
        <td>
            <?= \Yii::t($translate_category, 'Pay sum', [], $lang) ?>
        </td>
        <td>
            <?= $currency->getFormatted($order->pay_sum) ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= \Yii::t($translate_category, 'Hotel', [], $lang) ?>
        </td>
        <td colspan="5">
            <?= $order->hotel->{'title_'.$lang} ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= \Yii::t($translate_category, 'Contact information', [], $lang) ?>
        </td>
        <td colspan="5">
            <?= \common\components\ListAddressType::getTitle($order->contact_address, $lang) ?>
            <?= \yii\helpers\Html::encode($order->contact_name) ?> <?= \yii\helpers\Html::encode($order->contact_surname) ?>
            <br/>
            <a href="mailto:<?= \yii\helpers\Html::encode($order->contact_email) ?>"><?= \yii\helpers\Html::encode($order->contact_email) ?></a>
            <br/>
            <?= \yii\helpers\Html::encode($order->contact_phone) ?>
        </td>
    </tr>
</table>
<?= \Yii::t($translate_category, 'Numbers', [], $lang) ?>:
<table>
    <?php foreach($order->orderItems as $index => $item): ?>
        <?php /** @var \common\models\OrderItem $item */ ?>
        <tr>
            <td rowspan="2"><?= $index ?></td>
            <td><?= $item->room->{'title_' . $lang} ?></td>
            <td><?= \Yii::t($translate_category, 'Sum', [], $lang) ?></td>
            <td><?= $currency->getFormatted($item->sum) ?></td>
        </tr>
        <tr>
            <td colspan="3">
                <?= \Yii::t($translate_category, 'Adults: <b>{n}</b>', ['n' => $item->adults], $lang) ?>
                &nbsp;&nbsp;&nbsp;
                <?= \Yii::t($translate_category, 'Children 7-12 y.o.: <b>{n}</b>', ['n' => $item->children], $lang) ?>
                &nbsp;&nbsp;&nbsp;
                <?= \Yii::t($translate_category, 'Children 0-6 y.o.: <b>{n}</b>', ['n' => $item->kids], $lang) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

