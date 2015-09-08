<?php
use partner\models\PartnerUser;
use yii\helpers\Url;

/* @var PartnerUser $partner */


?>

<div class="alert alert-success" role="alert">
    <?= \Yii::t('partner_billing', 'Payment successfully completed', []) ?>
</div>

<div class="box">
    <div class="box-body">
        <h4>
            <?= \Yii::t('partner_billing', 'Balance of your account now:', []) ?> <b><?= $partner->billing->currency->getFormatted($partner->billing->balance) ?></b>
        </h4>

        <a href="/" class="btn btn-link"><?= \Yii::t('partner_billing', 'Go to main page', []) ?></a>
        <br>
        <a href="<?= Url::toRoute(['billing/transactions']) ?>" class="btn btn-link"><?= \Yii::t('partner_billing', 'View all billing transactions', []) ?></a>

    </div>
</div>
