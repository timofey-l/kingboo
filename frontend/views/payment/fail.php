<?php

/** @var \yii\web\View $this */
/** @var \common\models\Pay $pay */
/** @var \common\models\Order $order */


?>

<div>
	<h3 class="text-danger"><?= \Yii::t('frontend', 'The error occurred during the payment process!', []) ?></h3>
	<p>
		<?= \Yii::t('frontend', 'Try again later, by choosing a different payment method', []) ?>
	</p>
	<?= \yii\helpers\Html::a(\Yii::t('frontend', 'Try again'), ['show', 'id' => $order->payment_url], [
		'class' => 'btn btn-primary'
	]) ?>
</div>