<?php
/* @var $this yii\web\View */

$this->title = 'BooBooking';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">

            <div class="col-md-12">
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Partners'), ['/partner-user/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Hotels'), ['/hotel/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Hotel facilities '), ['/hotel-facilities/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Room facilities '), ['/room-facilities/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Orders'), ['/order/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'OrderItem'), ['/order-item/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Countries '), ['/country/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Currencies '), ['/currency/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Support'), ['/support/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Exchange rates'), ['/exchange-rates/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Logs'), ['/logs/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Pay logs'), ['/pay-log/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Pages'), ['/pages/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'FAQ'), ['/faq/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a('Gii', ['/gii'],['class'=>'btn btn-warning']) ?>
            </div>

        </div>

    </div>
</div>
