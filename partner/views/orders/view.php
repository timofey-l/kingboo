<?php

use common\models\Order;
use \DateTime;
use \yii\helpers\Html;
use \common\components\ListAddressType;
/* @var $this yii\web\View */
/* @var $order \common\models\Order */
$l = \common\models\Lang::$current->url;


$this->title = \Yii::t('partner_orders', 'Order #{n}', ['n' => $order->id]);
$this->params['showTitleAtTop'] = false;
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('partner_orders', 'Orders'),
	'url' => ['index'],
];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
$('#printButton').click(function(){
	$('body').addClass('sidebar-collapse');
	window.print();
});
");

?>

<?= \yii\helpers\Html::a(
    '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_orders', 'Back to all orders'),
    ['index'],
    ['class' => 'btn btn-default']) ?>

<?php if (!$order->viewed): ?>
    <?= \yii\helpers\Html::a(
        '<i class="fa fa-eye"></i> ' . \Yii::t('partner_orders', 'Set as viewed'),
        ['viewed', 'id' => $order->id],
        ['class' => 'btn btn-warning']) ?>
<?php endif; ?>
<?php if($order->status != Order::OS_PAYED && $order->status !== Order::OS_CANCELED && $order->payment_via_bank_transfer): ?>
	<?= \yii\helpers\Html::a(
		'<i class="fa fa-check"></i> ' . \Yii::t('partner_orders', 'Set as payed'),
		['payed', 'id' => $order->id],
		['class' => 'btn btn-success']) ?>
<?php endif; ?>

<?php if($order->status != Order::OS_CANCELED && (new DateTime($order->dateFrom))->diff(new DateTime())->invert): ?>
    <?= \yii\helpers\Html::a(
        '<i class="fa fa-trash"></i> ' . \Yii::t('partner_orders', 'Cancel order'),
        ['cancel', 'id' => $order->id],
        [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => Yii::t('partner_orders', 'Are you sure you want to cancel this order?'),
                'method'  => 'post',
            ],
        ]) ?>
<?php endif; ?>

<style>
	.invoice {
		margin: 30px 0 0 0;
	}

	@media print {
		.invoice {
			margin: 0;
		}
		footer {
			display: none;
		}
	}
</style>
<section class="invoice">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<?= \Yii::t('partner_orders','Order #{n}', ['n' => $order->id]) ?>
				<small class="pull-right"><samp><?= $order->number ?></samp></small>
			</h2>
		</div><!-- /.col -->
	</div>
	<div class="row invoice-info">
		<div class="col-sm-4 invoice-col">
			<strong><?= \Yii::t('partner_orders','Contact information') ?>:</strong>
			<address>

				<?= Html::encode(ListAddressType::getTitle($order->contact_address, $order->lang)) ?> <?= Html::encode($order->contact_name . ' ' . $order->contact_surname) ?>
				<br>

				<strong><?= \Yii::t('partner_orders','Phone') ?>:</strong>
				<br/>
				<?= $order->contact_phone ?>
				<br/>

				<strong><?= \Yii::t('partner_orders','Email') ?>:</strong>
				<br/>
				<?= $order->contact_email ?>

			</address>
		</div><!-- /.col -->
		<div class="col-sm-4 invoice-col">

			<strong><?= \Yii::t('partner_orders','Create time') ?>:</strong>
			<br/>
			<?= (new DateTime($order->created_at))->format(\Yii::t('partner_orders', 'd/m/Y H:i:s')) ?>
			<br/>

			<strong><?= \Yii::t('partner_orders','Dates range') ?>:</strong>
			<br/>
				<?= (new DateTime($order->dateFrom))->format(\Yii::t('partner_orders', 'd/m/Y'))
					. '&nbsp;&ndash;&nbsp;'
					. (new DateTime($order->dateTo))->format(\Yii::t('partner_orders', 'd/m/Y'))
			?>
			<br/>

			<strong><?= \Yii::t('partner_orders','Nights') ?>:</strong>
			<br/>
			<?= (new DateTime($order->dateTo))->diff(new DateTime($order->dateFrom))->days ?>
			<br/>

		</div><!-- /.col -->
		<div class="col-sm-4 invoice-col">

			<strong><?= \Yii::t('partner_orders','Order sum') ?>:</strong>
			<br/>
			<?= $order->sum ?> <?= $order->hotel->currency->code ?>
			<br/>

			<strong><?= \Yii::t('partner_orders','Pay sum') ?></strong>:
			<br/>
			<?= $order->pay_sum ?> <?= $order->hotel->currency->code ?>
			<br/>
			<?php if($order->partial_pay): ?>
				<?= \Yii::t('partner_orders','(partial pay {percents}%)', ['percents' => $order->partial_pay_percent]) ?>
			<?php endif; ?>
			<br/>


			<strong><?= \Yii::t('partner_orders','Pay status') ?></strong>:
			<br/>
			<?= \common\models\Order::getOrderStatusTitle($order->status) ?>
			<?php if($order->payment_via_bank_transfer): ?>
                <br>(<?= \Yii::t('partner_orders', 'bank transfer', []) ?>)
			<?php endif; ?>

		</div><!-- /.col -->
	</div>
	<br/>
	<div class="row">
		<div class="col-xs-12 table-responsive">
			<table class="table table-striped">
				<thead>
				<tr>
					<th><?= \Yii::t('partner_orders','Room and guest') ?></th>
					<th><?= \Yii::t('partner_orders','Adults') ?></th>
					<th><?= \Yii::t('partner_orders','Children') ?></th>
					<th><?= \Yii::t('partner_orders','Kids') ?></th>
					<th><?= \Yii::t('partner_orders','Sum') ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($order->orderItems as $item): /** @var \common\models\OrderItem $item */ ?>
					<tr>
						<td>
							<?= $item->room->{'title_' . $l} ?>
							<br/>
							<i>
								<?= ListAddressType::getTitle($item->guest_address) ?>
								<?= Html::encode($item->guest_name) ?>
								<?= Html::encode($item->guest_surname) ?>
							</i>
						</td>
						<td><?= $item->adults ?></td>
						<td><?= $item->children ?></td>
						<td><?= $item->kids ?></td>
						<td><?= $item->sum ?> <?= $order->hotel->currency->code ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div><!-- /.col -->
	</div>


	<div class="row no-print">
		<div class="col-xs-12">
			<button id="printButton" class="btn btn-default pull-right"><i class="fa fa-print"></i> <?= \Yii::t('partner_orders','Print') ?></button>
		</div>
	</div>
</section>
