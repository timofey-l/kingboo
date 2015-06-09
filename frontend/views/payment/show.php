<?php

/* @var $order \common\models\Order */

/* @var $this \yii\web\View */

/** @var \common\models\Hotel $hotel */
$hotel = $order->hotel;

$this->title = \Yii::t('frontend', 'Order payment');

$this->registerJs("
	$('#goToPayBtn').click(function(){
		$.ajax({
			url:'/payment/get-form',
			method: 'POST',
			data: {
				pay_type: $('input[name=payType]').val()
			}
		}).done(function(data){
			var \$form = $(atob(data));
			$('body').append(\$form);
			\$form.submit();
		});
	});
");

?>

<h4>
	<?= \Yii::t('frontend', 'The rooms that you chose were booked. <br/><br/>To complete the booking you need to pay.<br/><br/>Otherwise, booking will be canceled within {t} hours.', [
		't' => 24
	]) ?>
</h4>
<br/>

<div class="row">
	<div class="col-sm-5">
		<div class="panel panel-success order-info-panel">
			<div class="panel-heading">
				<?= \Yii::t('frontend', 'Order information') ?>
			</div>
			<div class="panel-body">
		<span class="dates-range">
			<span class=""><?= (new DateTime($order->dateFrom))->format('d/m') ?></span>
			&nbsp;&mdash;&nbsp;
			<span class=""><?= (new DateTime($order->dateTo))->format('d/m/Y') ?></span>
		</span>
				<br/>
				<?= \Yii::t('frontend', 'Nights: {n}', [
					'n' => (int)(new DateTime($order->dateTo))->diff(new DateTime($order->dateFrom))->days
				]) ?>
				<br/>
				<?= \Yii::t('frontend', 'Guests: {n}', [
					'n' => $order->orderItems[0]->adults + $order->orderItems[0]->children + $order->orderItems[0]->kids
				]) ?>
				<br/>
				<?= \Yii::t('frontend','Order number: <br/><code style="font-size:13px;">{n}</code>', ['n' => $order->number]) ?>
				<br/>
				<br/>
				<?= \Yii::t('frontend','Total sum:') ?>
				<br/>
				<span class="sum text-primary">
				<?= $order->sum ?>
					&nbsp;<?= $hotel->currency->symbol != "" ? $hotel->currency->symbol : $hotel->currency->code ?>
				</span>
				<br/>
				<br/>
				<?= \Yii::t('frontend','Sum to pay now:') ?>
				<br/>
				<span class="sum text-success">
				<?= $order->pay_sum ?>
					&nbsp;<?= $hotel->currency->symbol != "" ? $hotel->currency->symbol : $hotel->currency->code ?>
				</span>
			</div>
		</div>
	</div>
	<div class="col-sm-7">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<?= \Yii::t('frontend','Pay options') ?>
			</div>
			<div class="panel-body">
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="PC" checked>
						Оплата из кошелька в Яндекс.Деньгах
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="AC" checked>
						Оплата с произвольной банковской карты.
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="MC" checked>
						Платеж со счета мобильного телефона.
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="GP" checked>
						Оплата наличными через кассы и терминалы.
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="WM" checked>
						Оплата из кошелька в системе WebMoney.
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="SB" checked>
						Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн.
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="MP" checked>
						Оплата через мобильный терминал (mPOS).
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="AB" checked>
						Оплата через Альфа-Клик.
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="МА" checked>
						Оплата через MasterPass.
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="payType" id="" value="PB" checked>
						Оплата через Промсвязьбанк.
					</label>
				</div>
			</div>
			<div class="panel-footer" style="text-align: center;">
				<button class="btn btn-warning" id="goToPayBtn">
					<?= \Yii::t('frontend','Pay') ?>
				</button>
			</div>
		</div>
	</div>
</div>
