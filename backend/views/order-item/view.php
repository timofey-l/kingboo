<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_order', 'Order Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend_order', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend_order', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend_order', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'room_id',
            'order_id',
            'adults',
            'children',
            'kids',
            'guest_name',
            'guest_surname',
            'guest_address',
            'sum',
            'sum_currency_id',
            'pay_sum',
            'pay_sum_currency_id',
            'payment_system_sum',
            'payment_system_sum_currency_id',
        ],
    ]) ?>

</div>
