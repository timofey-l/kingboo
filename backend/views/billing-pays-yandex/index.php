<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BillingPaysYandexSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('billing_pays_yandex', 'Billing Pays Yandexes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-pays-yandex-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('billing_pays_yandex', 'Create Billing Pays Yandex'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'invoiceId',
            'payed:boolean',
            'checked:boolean',
            // 'check_post_dump:ntext',
            // 'avisio_post_dump:ntext',
            'billing_invoice_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
