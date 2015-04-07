<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('room_prices', 'Room Prices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-prices-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('room_prices', 'Create Room Prices'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
            'room_id',
            'adults',
            'children',
            // 'kids',
            // 'price',
            // 'price_currency',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
