<?php
/* @var $this yii\web\View */

$this->title = \Yii::t('partner_orders', 'Orders list');
$l = \common\models\Lang::$current;

$this->registerJs("

$('.orders_table tbody tr').click(function(e){
	var orderId = $(e.currentTarget).data('orderid');
	var l = LANG == 'ru' ? '' : LANG;
	window.location.assign('/' + l + 'orders/view?id=' + orderId)
	console.log($(e.currentTarget).data('orderid'));
});

$('#setAllAsViewed_btn').click(function(){
	var params = {};
	var csrf_param = $('meta[name=csrf-param]').attr('content');
	var csrf_value = $('meta[name=csrf-value]').attr('content');
	params[csrf_param] = csrf_value;
	$.post('/orders/set-all?type=viewed', params).done(function( data ) {
        if (data === true) {
            $('tr.new_order').removeClass('new_order');
        }
    });
});

");

?>
<style>
	.sum {
		font-size: 18px;
		font-weight: bold;
	}

	.table>tbody>tr>td {
		white-space: nowrap;
		cursor: pointer;
	}

	.new_order {
		background-color: #ffffd0;
	}
</style>
<div class="box">
	<div class="box-header">
		<h3 class="box-title"></h3>
		<button class="btn btn-default" id="setAllAsViewed_btn"><?= \Yii::t('partner_orders','Set all as viewed') ?></button>
	</div><!-- /.box-header -->
	<div class="box-body table-responsive no-padding">
		<table class="table table-hover orders_table">
			<thead>
				<tr>
					<th><?= \Yii::t('partner_orders','ID') ?></th>
					<th><?= \Yii::t('partner_orders','Date') ?></th>
					<th><?= \Yii::t('partner_orders','Status') ?></th>
					<th><?= \Yii::t('partner_orders','Dates range') ?></th>
					<th><?= \Yii::t('partner_orders','Hotel') ?></th>
					<th><?= \Yii::t('partner_orders','Contact info') ?></th>
					<th><?= \Yii::t('partner_orders','Rooms') ?></th>
					<th><?= \Yii::t('partner_orders','Order sum') ?></th>
					<th><?= \Yii::t('partner_orders','Pay sum') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($orders as $order): /** @var \common\models\Order $order */ ?>
				<?php
					$statusClass = "label";
					$statusText = "";
					switch($order->status) {
						case \common\models\Order::OS_CANCELED:
							$statusClass .= " label-danger";
							$statusText = \Yii::t('partner_orders', 'Cancelled');
							break;
						case \common\models\Order::OS_WAITING_PAY:
							$statusClass .= ' label-warning';
							$statusText = \Yii::t('partner_orders', 'Waiting payment');
							break;
						case \common\models\Order::OS_PAYED:
							$statusClass .= ' label-success';
							$statusText = \Yii::t('partner_orders', 'Payed');
							break;
					}?>
				<tr class="<?= !$order->viewed ? "new_order":"" ?>" data-orderid="<?= $order->id ?>">
					<td><?= $order->id ?></td>
					<td>
						<?= (new DateTime($order->created_at))->format(\Yii::t('partner_orders', 'd/m/Y'))?>
						<br/>
						<?= (new DateTime($order->created_at))->format(\Yii::t('partner_orders', 'H:i:s'))?>
					</td>
					<td>
						<?= \yii\helpers\Html::tag('span',$statusText, [
							'class' => $statusClass,
						]) ?>
					</td>
					<td>
						<?= (new \DateTime($order->dateFrom))->format(\Yii::t('partner_orders', 'd/m/Y')) ?>&nbsp;&ndash;&nbsp;<?= (new \DateTime($order->dateTo))->format(\Yii::t('partner_orders', 'd/m/Y')) ?>

					</td>
					<td>
						<?= $order->hotel->name ?>
					</td>
					<td>
						<?= \common\components\ListAddressType::getTitle($order->contact_address, $order->lang) ?>&nbsp;<?= \yii\helpers\Html::encode($order->contact_name) ?>&nbsp;<?= \yii\helpers\Html::encode($order->contact_surname) ?>
						<br/>
						<?= \yii\helpers\Html::a($order->contact_email, 'mailto:'.$order->contact_email) ?>
						<br/>
						<?php if (trim($order->contact_phone)) echo \yii\helpers\Html::a($order->contact_phone, 'tel:'.$order->contact_phone) ?>
					</td>
					<td>
						<?= $this->render('_rooms_list', [
							'order' => $order,
						]) ?>
					</td>
					<td>
						<span class="sum text-primary">
							<?= $order->sum ?>
						</span>&nbsp;<?= $order->hotel->currency->code ?>
					</td>
					<td>
						<span class="sum text-success">
							<?= $order->pay_sum ?>
						</span>&nbsp;<?= $order->hotel->currency->code ?>
						<?php if ($order->partial_pay): ?>
							<br/><small><?= $order->partial_pay_percent ?>%</small>
						<?php endif; ?>
					</td>

				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div><!-- /.box-body -->
</div>