<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

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
            'created_at',
            'updated_at',
            'number',
            'status',
            'hotel_id',
            'contact_email:email',
            'contact_phone',
            'contact_name',
            'contact_surname',
            'contact_address',
            'dateFrom',
            'dateTo',
            'sum',
            'sum_currency_id',
            'partial_pay:boolean',
            'partial_pay_percent',
            'pay_sum',
            'pay_sum_currency_id',
            'payment_system_sum',
            'payment_system_sum_currency_id',
            'lang',
            'viewed:boolean',
            'payment_url:url',
            'code',
            'partner_number',
            'checkin_fullpay',
            'payment_via_bank_transfer',
            'additional_info:ntext',
        ],
    ]) ?>

</div>
