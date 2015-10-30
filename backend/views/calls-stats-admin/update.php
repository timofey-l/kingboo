<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CallsStats */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Calls Stats',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Calls Stats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="calls-stats-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
