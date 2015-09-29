<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('partner_pays', 'Pays');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('partner_pays', 'Create Pay'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'orderCreatedDatetime',
            'paymentDatetime',
            'checked:boolean',
            'payed:boolean',
            'order_number',
            'invoiceId',
            // 'customerNumber',
            // 'orderSumAmount',
            // 'orderSumCurrencyPaycash',
            // 'orderSumBankPaycash',
            // 'shopSumAmount',
            // 'shopSumCurrencyPaycash',
            // 'shopSumBankPaycash',
            // 'paymentPayerCode',
            // 'paymentType',
            // 'postParams:ntext',
            'shopId',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
