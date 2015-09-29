<?php
/* @var $this yii\web\View */

$this->title = 'BooBooking';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">

            <div class="col-md-12">
                <h3><?= Yii::t('backend_main', 'Support') ?></h3>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Support'), ['/support/index'],['class'=>'btn btn-default']) ?>

                <h3><?= Yii::t('backend_main', 'Hotels') ?></h3>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Partners'), ['/partner-user/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Hotels'), ['/hotel/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Rooms'), ['/room/index'],['class'=>'btn btn-default']) ?>

                <h3><?= Yii::t('backend_main', 'Hotel payments') ?></h3>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Orders'), ['/order/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'OrderItem'), ['/order-item/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Pay'), ['/pay/index'],['class'=>'btn btn-default']) ?>

                <h3><?= Yii::t('backend_main', 'Billing') ?></h3>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Billing service'), ['/billing-service/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Billing account'), ['/billing-account/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Billing account services'), ['/billing-account-services/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Billing invoice'), ['/billing-invoice/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Billing income'), ['/billing-income/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Billing expense'), ['/billing-expense/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Billing pays Yandex'), ['/billing-pays-yandex/index'],['class'=>'btn btn-default']) ?>

                <h3><?= Yii::t('backend_main', 'Logs') ?></h3>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Logs'), ['/logs/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Pay logs'), ['/pay-log/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Billing pay logs'), ['/billing-logs/index'],['class'=>'btn btn-default']) ?>

                <h3><?= Yii::t('backend_main', 'Site') ?></h3>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Pages'), ['/pages/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'FAQ'), ['/faq/index'],['class'=>'btn btn-default']) ?>

                <h3><?= Yii::t('backend_main', 'Parameters') ?></h3>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Hotel facilities '), ['/hotel-facilities/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Room facilities '), ['/room-facilities/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Countries '), ['/country/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Currencies '), ['/currency/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Exchange rates'), ['/exchange-rates/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Pay methods'), ['/pay-method/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a('Gii', ['/gii'],['class'=>'btn btn-warning']) ?>
            </div>

        </div>

    </div>
</div>
