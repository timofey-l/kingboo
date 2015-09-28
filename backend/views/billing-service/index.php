<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BillingServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('billing_service', 'Billing Services');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-service-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('billing_service', 'Create Billing Service'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name_ru',
            'description_ru:ntext',
            'archived:boolean',
            'default:boolean',
            // 'monthly:boolean',
            // 'unique:boolean',
            // 'currency_id',
            // 'enable_cost',
            // 'monthly_cost',
            // 'name_en',
            // 'description_en:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
