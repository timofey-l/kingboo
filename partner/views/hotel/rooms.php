<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$hotel_title = $model->{'title_' . \common\models\Lang::$current->url};
$this->title = $hotel_title . ': ' . Yii::t('hotels', 'Rooms');

$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $hotel_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Rooms'), 'url' => ['rooms', 'id' => $model->id]];

//echo \common\helpers\DebugHelper::grid(new \common\models\RoomPrices());

?>
<div class="hotel-view">

    <?= $this->render('_rooms_manage',['hotel' => $model]) ?>

</div>
