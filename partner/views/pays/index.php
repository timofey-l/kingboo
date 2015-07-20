<?php

/** @var \yii\web\View $this */
use common\models\Currency;
use yii\grid\GridView;

/** @var \yii\data\ArrayDataProvider $dataDrovider */
$this->title = \Yii::t('partner_pays', 'Incoming payments');

$this->params['breadcrumbs'][] = \Yii::t('partner_pays', 'Incoming payments')

?>

<div class="box box-default">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'attribute' => 'paymentDatetime',
                    'format' => ['date', 'php:' . \Yii::t('partner_pays', 'd/m/Y H:i:s')],
                ],
                [
                    'attribute' => 'shopSumAmount',
                    'content' => function($model, $key, $index, $column) {
                        /** @var Currency $currency */
                        $currency = Currency::getByISOCode($model->shopSumCurrencyPaycash);
                        return $currency ? $currency->getFormatted($model->shopSumAmount) : $model->shopSumAmount . ' ['. $model->shopSumCurrencyPaycash.']';
                    },
                ],
                [
                    'attribute' => 'orderSumAmount',
                    'content' => function ($model, $key, $index, $column) {
                        /** @var Currency $currency */
                        $currency = Currency::getByISOCode($model->orderSumCurrencyPaycash);
                        return $currency ? $currency->getFormatted($model->orderSumAmount) : $model->orderSumAmount . ' [' . $model->orderSumCurrencyPaycash . ']';
                    }
                ],
                'invoiceId',
                [
                    'attribute' => 'order_number',
                    'content' => function ($model, $key, $index, $column) {
                        $order = $model->order;
                        $cell = "<code>{$order->partner_number}</code><br/>";
                        $cell .= \yii\helpers\Html::a(\Yii::t('partner_pays', 'Open order'),
                            ['orders/view', 'id' => $order->id],
                            ['class' => 'btn btn-link btn-xs']);

                        return $cell;
                    },
                    'contentOptions' => [
                        'style' => 'text-align: right;'
                    ]
                ]
            ],
        ]); ?>
    </div>
</div>
