<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend_order', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend_order', 'Create Order'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at',
            //'updated_at',
            'number',
            'status',
            // 'contact_email:email',
            // 'contact_phone',
            // 'contact_name',
            // 'contact_surname',
            // 'contact_address',
            // 'dateFrom',
            // 'dateTo',
            // 'sum',
            // 'partial_pay:boolean',
            // 'partial_pay_percent',
            // 'pay_sum',
            'hotel_id',
            // 'lang',
            // 'viewed:boolean',
            // 'payment_url:url',
            // 'code',
            'partner_number',
            // 'checkin_fullpay',
            // 'payment_via_bank_transfer',
            // 'additional_info:ntext',
            // 'sum_currency_id',
            // 'pay_sum_currency_id',
            // 'payment_system_sum',
            // 'payment_system_sum_currency_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
