<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel partner\models\PartnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('hotels', 'Hotels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('hotels', 'Create {modelClass}', ['modelClass' => 'Hotel',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
        },
    ]) ?>

</div>
