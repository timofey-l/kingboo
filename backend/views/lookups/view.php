<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\LookupField */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Lookup Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lookup-field-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php var_dump($model->values); ?>
    <p>
        <?= Html::a(Yii::t('backend_models', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend_models', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend_models', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        ],
    ]) ?>

</div>
