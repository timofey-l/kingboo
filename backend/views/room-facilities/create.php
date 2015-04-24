<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RoomFacilities */

$this->title = Yii::t('room_facilities', 'Create Room Facilities');
$this->params['breadcrumbs'][] = ['label' => Yii::t('room_facilities', 'Room Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-facilities-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
