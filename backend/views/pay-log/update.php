<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PayLog */

$this->title = Yii::t('backend_paylog', 'Update {modelClass}: ', [
    'modelClass' => 'Pay Log',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_paylog', 'Pay Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend_paylog', 'Update');
?>
<div class="pay-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
