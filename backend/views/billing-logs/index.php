<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BillingLogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('billing-logs', 'Billing Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-logs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('billing-logs', 'Create Billing Logs'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'type',
            'date',
            'code',
            'notes',
            //'postParams:ntext',
            //'serverParams:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
