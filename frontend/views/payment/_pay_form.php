<?php
?>
<form action="<?= $actionURL ?>" method="post">

	<input type="hidden" name="shopId" value="<?= $partner->shopId ?>"/>
	<input type="hidden" name="scid" value="<?= $partner->scid ?>"/>
	<input type="hidden" name="sum" value="<?= $sum ?>"/>
	<input type="hidden" name="customerNumber" value="<?= md5($order->contact_email) ?>"/>
	<input type="hidden" name="paymentType" value="<?= $paymentType ?>"/>
	<input type="hidden" name="orderNumber" value="<?= $order->number ?>"/>
	<input type="hidden" name="scp_phone" value="<?= $order->contact_phone ?>"/>
	<input type="hidden" name="scp_email" value="<?= $order->contact_email ?>"/>
	<input type="hidden" name="shopSuccessUrl" value="<?= $shopSuccessURL ?>"/>
	<input type="hidden" name="shopFailUrl" value="<?= $shopFailURL ?>"/>

</form>