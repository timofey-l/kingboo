<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Widget */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_widget', 'Widgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-view">

    <p>
        <?= Html::a(Yii::t('partner_widget', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('partner_widget', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('partner_widget', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'hotel_id',
            'hash_code',
            'params:ntext',
            'comment',
            'compiled_js:ntext',
            'compiled_css:ntext',
        ],
    ]) ?>

</div>
