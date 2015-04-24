<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RoomFacilities */

$this->title = Yii::t('room_facilities', 'Update {modelClass}: ', [
    'modelClass' => 'Room Facilities',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('room_facilities', 'Room Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('room_facilities', 'Update');
?>
<div class="room-facilities-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
