<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */
/* @var $lang string */
/* @var $local string */

if (!isset($lang)) {
    $lang = $order->hotel->partner->lang;
}
?>

<div>
    <p><?= \Yii::t('mails_order', 'Hello!', [], $local) ?></p>
    <p><?= \Yii::t('mails_order', 'Order #{n} has been made on site <a href="https://king-boo.com">king-boo.com</a>.', ['n' => $order->partner_number], $local) ?></p>
    <p><?= \Yii::t('mails_order', 'You can open order to manage it by <a href="{url}">clicking here</a>.', ['url' => \yii\helpers\Url::to('@partner_web').'/orders/view?id='.$order->id]) ?></p>
    <p><?= \Yii::t('mails_order', 'Order details') ?>:</p>
    <p><?= $this->render('_order-html', ['order' => $order, 'lang' => $lang, 'local' => $local]) ?></p>

    <p></p>

    <p><?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $local) ?></p>
</div>