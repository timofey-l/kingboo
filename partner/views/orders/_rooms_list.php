<?php
/** @var \common\models\Order $order */
$items = $order->orderItems;
$l = \common\models\Lang::$current;
?>

	<style>
		.room_item {

		}

		.room_item:not(:last-child) {
			margin-bottom: 10px;
		}
	</style>

<?php foreach ($items as $index => $item): ?>
	<?php /** @var \common\models\OrderItem $item */ ?>
	<div class="room_item">
		<span class="room_title">
			<?= $item->room->{'title_' . $l->url} ?>
		</span>
		<br/>
		<span class="label label-default">
			<?= \Yii::t('partner_orders', 'Adults: {n}', ['n' => $item->adults]) ?>
		</span>
		&nbsp;
		<span class="label label-default">
			<?= \Yii::t('partner_orders', 'Children: {n}', ['n' => $item->children]) ?>
		</span>
		&nbsp;
		<span class="label label-default">
			<?= \Yii::t('partner_orders', 'Kids: {n}', ['n' => $item->kids]) ?>
		</span>
	</div>
<?php endforeach; ?>