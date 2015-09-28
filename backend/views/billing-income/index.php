<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BillingIncomeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('billing_income', 'Billing Incomes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-income-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('billing_income', 'Create Billing Income'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sum',
            'date',
            'account_id',
            'invoice_id',
            // 'currency_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
