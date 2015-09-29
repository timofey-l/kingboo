<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Pay */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_pays', 'Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('partner_pays', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('partner_pays', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('partner_pays', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'checked:boolean',
            'payed:boolean',
            'order_number',
            'invoiceId',
            'customerNumber',
            'orderCreatedDatetime',
            'paymentDatetime',
            'orderSumAmount',
            'orderSumCurrencyPaycash',
            'orderSumBankPaycash',
            'shopSumAmount',
            'shopSumCurrencyPaycash',
            'shopSumBankPaycash',
            'paymentPayerCode',
            'paymentType',
            [
                'attribute' => 'postParams',
                'format' => 'raw',
                'value' => "<pre>".var_export(unserialize($model->postParams), true)."</pre>",
            ],
            'shopId',
        ],
    ]) ?>

</div>
