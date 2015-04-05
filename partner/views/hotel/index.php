<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $hotels \common\models\Hotel[] */
$this->title = Yii::t('hotels', 'Hotels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('hotels', 'Create {modelClass}', ['modelClass' => 'Hotel',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    foreach($hotels as $hotel) {
        echo $this->render('_hotel_index', ['hotel' => $hotel]);
    }
    ?>

</div>
