<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'partner_id',
            'name',
            'address_ru',
            'address_en',
            'lng',
            'lat',
            'description_ru:ntext',
            'category',
            'timezone',
            'description_en:ntext',
            'title_ru',
            'title_en',
            'currency_id',
            'allow_partial_pay:boolean',
            'partial_pay_percent',
            'domain',
            'less:ntext',
            'css:ntext',
            'contact_phone',
            'contact_email:email',
        ],
    ]) ?>

</div>
