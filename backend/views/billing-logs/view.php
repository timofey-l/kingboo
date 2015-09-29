<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BillingLogs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing-logs', 'Billing Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-logs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('billing-logs', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('billing-logs', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('billing-logs', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'date',
            [
                'attribute' => 'postParams',
                'format' => 'raw',
                'value' => "<pre>".var_export(unserialize($model->postParams), true)."</pre>",
            ],
            [
                'attribute' => 'serverParams',
                'format' => 'raw',
                'value' => "<pre>".var_export(unserialize($model->serverParams), true)."</pre>",
            ],
            'code',
            'notes',
        ],
    ]) ?>

</div>
