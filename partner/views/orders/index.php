<?php
/* @var $this yii\web\View */

$this->title = \Yii::t('partner_orders', 'Orders list');
$l = \common\models\Lang::$current;
$assetOptions = ['depends' => [
    \partner\assets\AppAsset::className(),
    \dmstr\web\AdminLteAsset::className(),
]];


$this->params['breadcrumbs'][] = $this->title;

// iCheck plugin
$icheck = \Yii::$app->assetManager->publish('@vendor/almasaeed2010/adminlte/plugins/iCheck');
//$this->registerCssFile($icheck[1] . '/all.css', $assetOptions);
$this->registerCssFile($icheck[1] . '/flat/flat.css', $assetOptions);
$this->registerCssFile($icheck[1] . '/flat/blue.css', $assetOptions);
$this->registerJsFile($icheck[1] . '/icheck.min.js', $assetOptions);


$this->registerJs("

var checked = 0;

$('.iCheck').iCheck({
    checkboxClass: 'icheckbox_flat-blue'
});

$('.iCheck').on('ifToggled', function(event) {
    updateGroup(event.target);
});

function updateGroup() {
    checked = $('.iCheck:checked').length;
    if (!checked) {
        $('.group-actions').slideUp();
    } else {
        $('.group-actions').slideDown();
    }
}

$('.orders_table tbody tr').click(function(e){
	var orderId = $(e.currentTarget).data('orderid');
	var l = LANG == 'ru' ? '' : LANG;
	window.location.assign('/' + l + 'orders/view?id=' + orderId);
	console.log($(e.currentTarget).data('orderid'));
});

$('#setAllAsViewed_btn').click(function(){
	var params = {};
	var csrf_param = $('meta[name=csrf-param]').attr('content');
	var csrf_value = $('meta[name=csrf-value]').attr('content');

    params.ids = [];
	$('.iCheck:checked').each(function(i, el){
	    params.ids.push($(el).data('id'));
	});

	params[csrf_param] = csrf_value;
	$('.box>.overlay').fadeIn();
	$.post('/orders/set-all?type=viewed', params).done(function( data ) {
	    $('.box>.overlay').fadeOut();
        if (data === true) {
            $.each(params.ids, function(i, el){
                $('tr.id'+el).removeClass('new_order');
                $('.iCheck').iCheck('uncheck');
            });
//            $('tr.new_order').removeClass('new_order');
        }
    }).error(function(){
        $('.box>.overlay').fadeOut();
    });
});
updateGroup();
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


<?php if (!$orders): ?>
    <div class="callout callout-info">
        <h4><i class="icon fa fa-info"></i> <?= \Yii::t('partner_orders', 'Orders are absent') ?></h4>
        <?= \Yii::t('partner_orders', 'No orders have been made yet.') ?>
    </div>
<?php endif; ?>

<?php if ($orders): ?>
<div class="row group-actions" style="display: none;">
    <div class="col-md-12">
        <button class="btn btn-default"
                id="setAllAsViewed_btn"><i class="fa fa-eye"></i> <?= \Yii::t('partner_orders', 'Set as viewed') ?></button>
    </div>
</div>
<br/>

<div class="box loading">
	<div class="box-body table-responsive no-padding">
		<table class="table table-hover orders_table">
			<thead>
				<tr>
                    <th></th>
					<th><?= \Yii::t('partner_orders','Number') ?></th>
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
						case \common\models\Order::OS_CHECKIN_FULLPAY:
							$statusClass .= ' label-success';
							$statusText = \Yii::t('partner_orders', 'Full pay at check in');
							break;
					}?>
				<tr class="<?= !$order->viewed ? "new_order":"" ?> id<?= $order->id ?>" data-orderid="<?= $order->id ?>">
                    <td>
                        <input class="iCheck" type="checkbox" data-id="<?= $order->id ?>" id="order_<?= $order->id ?>"/>
                    </td>
					<td><?= $order->partner_number ?></td>
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
    <div class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
<?php endif; ?>
