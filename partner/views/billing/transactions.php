<?php

/* @var $this yii\web\View */
use common\models\Currency;
use yii\db\Query;
use yii\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $expensesQuery Query */
/* @var $incomesQuery Query */

$this->title = Yii::t('partner_transactions', 'Billing transactions');

$this->params['breadcrumbs'] = [$this->title];

?>


<div class="box">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [//type
                    'label' => \Yii::t('partner_transactions', 'Type'),
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $column) {
                        switch ($model['type']) {
                            case "1":
                                return '<i class="fa fa-plus text-success"></i> '.\Yii::t('partner_transactions', 'Income', []);
                                break;

                            case "2":
                                return '<i class="fa fa-minus text-danger"></i> '.\Yii::t('partner_transactions', 'Expense', []);
                                break;
                        }
                    }
                ],
                [//date
                    'attribute' => 'date',
                    'label' => \Yii::t('partner_transactions', 'Date')],
                [
                    'label' => \Yii::t('partner_transactions', 'Sum'),
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $column) {
                        /** @var Currency $currency */
                        $currency = Currency::findOne($model['currency_id']);
                        $val = (float) $model['sum'];
                        if ($currency) {
                            $val = $currency->getFormatted($val);
                        }
                        return $val;
                    }
                ],
                'comment:ntext',
            ],
        ]) ?>

    </div>
</div>