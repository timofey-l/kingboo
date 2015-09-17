<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\HotelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hotels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Hotel'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'partner_id',
            'name',
            //'address_ru',
            //'address_en',
            // 'lng',
            // 'lat',
            // 'description_ru:ntext',
            // 'category',
            // 'timezone',
            // 'description_en:ntext',
            'title_ru',
            'title_en',
            // 'currency_id',
            // 'allow_partial_pay:boolean',
            // 'partial_pay_percent',
            // 'domain',
            // 'less:ntext',
            // 'css:ntext',
            // 'contact_phone',
            // 'contact_email:email',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
