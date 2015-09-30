<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PayLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_paylog', 'Pay Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-log-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend_paylog', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend_paylog', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend_paylog', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'timestamp',
            'type',
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
