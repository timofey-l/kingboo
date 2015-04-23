<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\HotelFacilities */

$this->title = Yii::t('hotel_facilities', 'Create Hotel Facilities');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel_facilities', 'Hotel Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-facilities-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
