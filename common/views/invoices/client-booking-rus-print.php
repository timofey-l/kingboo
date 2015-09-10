<?php
//onload="window.print();"
use yii\helpers\Html;
use \common\models\Currency;
/* @var $lang string */

$assetManager = \Yii::$app->assetManager;
$adminlte = dmstr\web\AdminLteAsset::register($this);
$this->params['adminlte'] = $adminlte->baseUrl;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$directoryAsset = $adminlte->baseUrl;

if (!isset($l)) {
    $l = $order->hotel->partner->lang;
}
?>
<?php $this->beginPage() ?>
<!doctype html>
<html>
<head>
    <title>Бланк "Счет на оплату"</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>    
    <style>
        body { width: 210mm; margin-left: auto; margin-right: auto; border: 1px #efefef solid; font-size: 11pt;}
        table.invoice_bank_rekv { border-collapse: collapse; border: 1px solid; }
        table.invoice_bank_rekv > tbody > tr > td, table.invoice_bank_rekv > tr > td { border: 1px solid; }
        table.invoice_items { border: 1px solid; border-collapse: collapse;}
        table.invoice_items td, table.invoice_items th { border: 1px solid;}
    </style>
</head>
<body onload="window.print();">
<?php $this->beginBody() ?>
<table width="100%">
    <tr>
        <td>&nbsp;</td>
        <td style="width: 155mm;">
            <div style="width:155mm; ">Внимание! Оплата данного счета означает согласие с условиями бронировния.</div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div style="text-align:center;  font-weight:bold;">
                Образец заполнения платежного поручения                                                                                                                                            
            </div>
        </td>
    </tr>
</table>


<table width="100%" cellpadding="2" cellspacing="2" class="invoice_bank_rekv">
    <tr>
        <td colspan="2" rowspan="2" style="min-height:13mm; width: 105mm;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="height: 13mm;">
                <tr>
                    <td valign="top">
                        <div><?= Html::encode($paymentDetails->bank); ?></div>
                    </td>
                </tr>
                <tr>
                    <td valign="bottom" style="height: 3mm;">
                        <div style="font-size:10pt;">Банк получателя        </div>
                    </td>
                </tr>
            </table>
        </td>
        <td style="min-height:7mm;height:auto; width: 25mm;">
            <div>БИK</div>
        </td>
        <td rowspan="2" style="vertical-align: top; width: 60mm;">
            <div style=" height: 7mm; line-height: 7mm; vertical-align: middle;"><?= Html::encode($paymentDetails->BIK) ?></div>
            <div><?= Html::encode($paymentDetails->cAccount) ?></div>
        </td>
    </tr>
    <tr>
        <td style="width: 25mm;">
            <div>Сч. №</div>
        </td>
    </tr>
    <tr>
        <td style="min-height:6mm; height:auto; width: 50mm;">
            <div>ИНН <?= Html::encode($paymentDetails->INN) ?></div>
        </td>
        <td style="min-height:6mm; height:auto; width: 55mm;">
            <div>КПП <?= Html::encode($paymentDetails->KPP) ?></div>
        </td>
        <td rowspan="2" style="min-height:19mm; height:auto; vertical-align: top; width: 25mm;">
            <div>Сч. №</div>
        </td>
        <td rowspan="2" style="min-height:19mm; height:auto; vertical-align: top; width: 60mm;">
            <div><?= Html::encode($paymentDetails->account) ?></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="min-height:13mm; height:auto;">

            <table border="0" cellpadding="0" cellspacing="0" style="height: 13mm; width: 105mm;">
                <tr>
                    <td valign="top">
                        <div><?= Html::encode($paymentDetails->firmName); ?></div>
                    </td>
                </tr>
                <tr>
                    <td valign="bottom" style="height: 3mm;">
                        <div style="font-size: 10pt;">Получатель</div>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<br/>

<div style="font-weight: bold; font-size: 16pt; padding-left:5px;">
	<?php $date = new \DateTime($order->created_at); ?>
    Счет № <?= Html::encode($order->partner_number) ?> от <?= Html::encode($date->format(\Yii::t('partner_orders', 'd/m/Y'))) ?></div>
<br/>

<div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>

<table width="100%">
    <tr>
        <td style="width: 30mm;">
            <div style=" padding-left:2px;">Поставщик:    </div>
        </td>
        <td>
            <div style="font-weight:bold;  padding-left:2px;">
                <?= Html::encode($paymentDetails->firmName); ?>            </div>
        </td>
    </tr>
    <tr>
        <td style="width: 30mm;">
            <div style=" padding-left:2px;">Покупатель:    </div>
        </td>
        <td>
            <div style="font-weight:bold;  padding-left:2px;">
                <?= Html::encode($order->contact_surname); ?> <?= Html::encode($order->contact_name); ?>           </div>
        </td>
    </tr>
</table>


<table class="invoice_items" width="100%" cellpadding="2" cellspacing="2">
    <thead>
    <tr>
        <th style="width:13mm;">№</th>
        <th style="width:20mm;">Код</th>
        <th>Товар</th>
        <th style="width:20mm;">Кол-во</th>
        <th style="width:17mm;">Ед.</th>
        <th style="width:27mm;">Цена</th>
        <th style="width:27mm;">Сумма</th>
    </tr>
    <?php 
    $c = Currency::findOne(['code' => 'RUB']);
    for ($i=0; $i<count($order->orderItems); $i++) { 
    ?>
    <tr>
    	<td><?= Html::encode($i+1); ?></td>
    	<td><?= Html::encode($order->number); ?></td>
    	<td>
    		Проживание в 
    		&laquo;<?= Html::encode($order->orderItems[$i]->room->{'title_' . $l}); ?>&raquo;
    		с <?= (new DateTime($order->dateFrom))->format(\Yii::t('partner_orders', 'd/m/Y')); ?>
			по <?= (new DateTime($order->dateTo))->format(\Yii::t('partner_orders', 'd/m/Y'))?>
    	</td>
    	<td>1</td>
    	<td>шт.</td>
    	<td><?= Html::encode($c->getFormatted($order->orderItems[$i]->payment_system_sum, 'invoice')); ?></td>
    	<td><?= Html::encode($c->getFormatted($order->orderItems[$i]->payment_system_sum, 'invoice')); ?></td>
    </tr>
    <?php } ?>
    </thead>
    <tbody >
    </tbody>
</table>

<table border="0" width="100%" cellpadding="1" cellspacing="1">
    <tr>
        <td></td>
        <td style="width:38mm; font-weight:bold;  text-align:right;">Итого без НДС:</td>
        <td style="width:38mm; font-weight:bold;  text-align:right;">
            <?php 
                if ($paymentDetails->NDS > 0) {
                    $v = round($order->payment_system_sum * (1 - $paymentDetails->NDS / 100), 2);
                } else {
                    $v = $order->payment_system_sum;
                }
                echo Html::encode($c->getFormatted($v, 'invoice')); 
            ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="width:38mm; font-weight:bold;  text-align:right;">
            Итого НДС<?php 
                if ($paymentDetails->NDS > 0) {
                    echo ' (' . $paymentDetails->NDS . '%):';
                } else {
                    echo ':';
                }
            ?>
        </td>
        <td style="width:38mm; font-weight:bold;  text-align:right;">
            <?php 
                if ($paymentDetails->NDS > 0) {
                    $v = round($order->payment_system_sum * $paymentDetails->NDS / 100, 2);
                    $v = Html::encode($c->getFormatted($v, 'invoice'));
                } else {
                    $v = '---';
                }
                echo $v; 
            ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="width:38mm; font-weight:bold;  text-align:right;">Всего к оплате:</td>
        <td style="width:38mm; font-weight:bold;  text-align:right;"><?= Html::encode($c->getFormatted($order->payment_system_sum, 'invoice')) ?></td>
    </tr>
</table>

<br />
<div>
Всего наименований <?= count($order->orderItems) ?> на сумму <?= Html::encode($c->getFormatted($order->payment_system_sum, 'invoice')) ?><br />
<?php  
	$rub = floor($order->payment_system_sum);
	$kop = round($order->payment_system_sum - $rub, 2) * 100;
	$fmt = numfmt_create( 'ru', NumberFormatter::SPELLOUT );
	$rub_text = numfmt_format($fmt, $rub);
	$rub_text = mb_strtoupper(substr($rub_text, 0, 2), 'UTF-8') . substr($rub_text, 2);
?>
<?= $rub_text . ' ' . \Yii::t('invoice', '{rub, plural, =0{rubles} one{ruble} few{ubles} many{rubles} other{rubles}}', ['rub' => $rub]) . ' '
	. $kop . ' ' . Yii::t('invoice', '{kop, plural, =0{kopecks} one{kopeck} few{kopecks} many{kopecks} other{kopecks}}', ['kop' => $kop]); ?>
</div>
<br /><br />
<div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>
<br/>

<div>Руководитель ______________________ (<?= Html::encode($paymentDetails->director); ?>)</div>
<br/>
<?php if (strlen($paymentDetails->bookkeeper) > 0) : ?>
<div>Гл. бухгалтер&nbsp; ______________________ (<?= Html::encode($paymentDetails->bookkeeper); ?>)</div>
<br/>
<?php endif; ?>

<div style="width: 85mm;text-align:center;">М.П.</div>
<br/>


<div style="width:800px;text-align:left;font-size:10pt;">Счет действителен к оплате в течении трех дней.</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>