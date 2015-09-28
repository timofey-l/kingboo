<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BillingLogs */

$this->title = Yii::t('billing-logs', 'Update {modelClass}: ', [
    'modelClass' => 'Billing Logs',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing-logs', 'Billing Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('billing-logs', 'Update');
?>
<div class="billing-logs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
