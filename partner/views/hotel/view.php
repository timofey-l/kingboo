<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$this->title = $model->{'title_' . \common\models\Lang::$current->url};

$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="hotel-view">

    <p>
        <?= Html::a(Yii::t('hotels', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('hotels', 'Facilities'), ['facilities', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('hotels', 'Rooms'), ['rooms', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('hotels', 'Images'), ['images', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('hotels', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('hotels', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'address',
        ],
    ]) ?>
    

</div>
