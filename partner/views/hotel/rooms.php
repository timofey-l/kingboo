<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$this->title = Yii::t('hotels', 'Rooms');

$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->{'title_' . \common\models\Lang::$current->url}, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="hotel-view">

    <?= $this->render('_rooms_manage',['hotel' => $model]) ?>

</div>
