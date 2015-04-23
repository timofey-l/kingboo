<?php
/* @var $this yii\web\View */

$this->title = 'BooBooking';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">

            <div class="col-md-12">
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Partners'), ['/partner-user/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Types of food'), ['/food-type/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Hotel facilities '), ['/hotel-facilities/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Countries '), ['/country/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Currencies '), ['/currency/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Clients'), ['/user/index'],['class'=>'btn btn-default']) ?>
                <?= \yii\helpers\Html::a('Gii', ['/gii'],['class'=>'btn btn-warning']) ?>
            </div>

        </div>

    </div>
</div>
