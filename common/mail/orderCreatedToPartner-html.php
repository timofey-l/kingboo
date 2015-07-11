<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */
/* @var $lang string */

if (!isset($lang)) {
    $lang = $order->hotel->partner->lang;
}
?>

<div>
    <p><?= \Yii::t('mails_order', 'Hello!', [], $lang) ?></p>
    <p><?= \Yii::t('mails_order', 'Order #{n} has been made on site <a href="http://king-boo.com">king-boo.com</a>.', ['n' => $order->partner_number], $lang) ?></p>
    <p><?= \Yii::t('mails_order', 'You can open order to manage it by <a href="{url}">clicking here</a>.', ['url' => \yii\helpers\Url::to('@partner_web').'/orders/view?id='.$order->id]) ?></p>
    <p><?= \Yii::t('mails_order', 'Order details') ?>:</p>
    <p><?= $this->render('_order-html', ['order' => $order]) ?></p>

    <p></p>

    <p><?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $lang) ?></p>
</div>