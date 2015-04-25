<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HotelFacilities */

$this->title = Yii::t('hotel_facilities', 'Update {modelClass}: ', [
    'modelClass' => 'Hotel Facilities',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel_facilities', 'Hotel Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('hotel_facilities', 'Update');
?>
<div class="hotel-facilities-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
