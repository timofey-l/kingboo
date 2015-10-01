<?php

use yii\grid\GridView;
use common\models\Currency;

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

$orders = $dataProvider->getModels();
function getStatusText($order) {
	switch($order->status) {
		case \common\models\Order::OS_CANCELED:
			return \Yii::t('partner_orders', 'Cancelled');
		case \common\models\Order::OS_WAITING_PAY:
			return \Yii::t('partner_orders', 'Waiting payment');
		case \common\models\Order::OS_PAYED:
			return \Yii::t('partner_orders', 'Payed');
		case \common\models\Order::OS_CHECKIN_FULLPAY:
			return \Yii::t('partner_orders', 'Full pay at check in');
	}
	return '';
}
function getStatusClass($order) {
	switch($order->status) {
		case \common\models\Order::OS_CANCELED:
			return 'label label-danger';
		case \common\models\Order::OS_WAITING_PAY:
			return 'label label-warning';
		case \common\models\Order::OS_PAYED:
			return 'label label-success';
		case \common\models\Order::OS_CHECKIN_FULLPAY:
			return 'label label-success';
	}
}
?>
<style>
	.sum {font-size: 18px; font-weight: bold;}
	.table>tbody>tr>td {white-space: nowrap; cursor: pointer;}
	.new_order {background-color: #ffffd0;}
	.summary {padding: 10px;}
	.pagination {padding-left: 10px;}
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
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover orders_table'],
            'summaryOptions' => ['class' => 'summary'],
            'rowOptions' => function ($model, $key, $index, $grid) {
            	return ['class' => ($model->viewed ? '' : 'new_order ') . "id$key", 'data-orderid' => $key];
            },
            'columns' => [
            	[
            		'class' => 'yii\grid\CheckboxColumn',
            		'header' => '&nbsp;',
            		'checkboxOptions' => function ($model, $key, $index, $column) {
    					return ['class' => 'iCheck', 'data-id' => $key, 'id' => "order_$key"];
					}
            	],
                [
                    'attribute' => 'number',
                    'content' => function ($model, $key, $index, $column) {
                    	return $model->partner_number . '<br />' . $model->number;
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'label' => \Yii::t('partner_orders','Date'),
                    'content' => function ($model, $key, $index, $column) {
                    	return (new DateTime($model->created_at))->format(\Yii::t('partner_orders', 'd/m/Y')) . '<br/>'
						. (new DateTime($model->created_at))->format(\Yii::t('partner_orders', 'H:i:s'));
                    }
                ],
                [
                    'attribute' => 'status',
                    'content' => function($model, $key, $index, $column) {
                        return \yii\helpers\Html::tag('span', getStatusText($model), ['class' => getStatusClass($model)]); 
                    }
                ],
                [
                    'attribute' => 'dateFrom',
                    'label' => \Yii::t('partner_orders','Dates range'),
                    'content' => function ($model, $key, $index, $column) {
                        return (new \DateTime($model->dateFrom))->format(\Yii::t('partner_orders', 'd/m/Y')) . '&nbsp;&ndash;&nbsp;' 
                        	. (new \DateTime($model->dateTo))->format(\Yii::t('partner_orders', 'd/m/Y'));
                    }
                ],
                [
                	'attribute' => 'hotel_id',
                	'label' => \Yii::t('partner_orders','Hotel'),
                	'content' => function ($model, $key, $index, $column) {
                		return $model->hotel->name;
                	}
                ],
                [
                    'attribute' => 'contact_name',
                    'label' => \Yii::t('partner_orders','Contact info'),
                    'content' => function ($model, $key, $index, $column) {
                        return \common\components\ListAddressType::getTitle($model->contact_address, $model->lang) . '&nbsp;' 
                        	. \yii\helpers\Html::encode($model->contact_name) . '&nbsp;' . \yii\helpers\Html::encode($model->contact_surname) 
							. '<br/>' . \yii\helpers\Html::a($model->contact_email, 'mailto:'.$model->contact_email)
							. '<br/>' . ((trim($model->contact_phone)) ? \yii\helpers\Html::a($model->contact_phone, 'tel:'.$model->contact_phone) : '');
                    },
                ],
                [
                	'label' => \Yii::t('partner_orders','Rooms'),
                	'content' => function ($model, $key, $index, $column) {
                		return $this->render('_rooms_list', ['order' => $model]);
                	}
                ],
                [
                	'attribute' => 'sum',
                	'content' => function ($model, $key, $index, $column) {
                		$c = Currency::findOne($model->sum_currency_id);
                		if ($c) {
                			return '<span class="sum text-primary">' . $c->getFormatted($model->sum) . '</span>';
                		} else {
                			return '<span class="sum text-primary">' . $model->sum . '</span>';
                		}
                	}
                ],
                [
                	'attribute' => 'pay_sum',
                	'label' => \Yii::t('partner_orders','Pay sum'),
                	'content' => function ($model, $key, $index, $column) {
                		$c = Currency::findOne($model->pay_sum_currency_id);
                		$payment_system_sum = '';
                		if ($model->pay_sum_currency_id != $model->payment_system_sum_currency_id) {
                			$c1 = Currency::findOne($model->payment_system_sum_currency_id);
                			$payment_system_sum = $c1 ? '<br />' . $c1->getFormatted($model->payment_system_sum) : '<br />' . $model->payment_system_sum;
                		}
                		return '<span class="sum text-success">' . ($c ? $c->getFormatted($model->pay_sum) : $model->pay_sum) . '</span>'
							. ($model->partial_pay ? '&nbsp;<small>(' . $model->partial_pay_percent . '%)</small>' : '') . $payment_system_sum; 
                	}
                ],
            ],
        ]); ?>
	</div><!-- /.box-body -->
    <div class="overlay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>

<?php endif; ?>
