<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$this->title = Yii::t('hotels', 'Update {modelClass}: ', [
    'modelClass' => 'Hotel',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('hotels', 'Update');
?>
<div class="hotel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
