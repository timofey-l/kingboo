<?php
use yii\helpers\ArrayHelper;
?>
<?= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" ?>
<checkOrderResponse performedDatetime="<?= date(\DateTime::W3C) ?>" code="<?= $code ?>" invoiceId="<?= ArrayHelper::getValue($post, 'invoiceId', 0) ?>" shopId="<?= ArrayHelper::getValue($post, 'shopId', 0) ?>" message="<?= $message ?>" />