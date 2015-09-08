<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend_order', 'Order Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend_order', 'Create Order Item'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'room_id',
            'order_id',
            //'adults',
            //'children',
            // 'kids',
            'sum',
            // 'guest_name',
            // 'guest_surname',
            // 'guest_address',
            'sum_currency_id',
            // 'pay_sum',
            // 'pay_sum_currency_id',
            // 'payment_system_sum',
            // 'payment_system_sum_currency_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
