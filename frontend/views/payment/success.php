<?php

/** @var \yii\web\View $this */
/** @var \common\models\Pay $pay */
/** @var \common\models\Order $order */

?>

<div>
	<h1><?= \Yii::t('frontend', 'Thank you for your payment!', []) ?></h1>

	<p>
		<?= \Yii::t('frontend', 'Your order #{n} has been paid.', ['n' => $order->number]) ?>
	</p>
</div>