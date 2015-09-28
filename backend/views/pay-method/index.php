<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PayMethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('pay-method', 'Pay Methods');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-method-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('pay-method', 'Create Pay Method'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title_ru',
            'title_en',
            'yandex_code',
            'order',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
