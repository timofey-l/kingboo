<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BillingService */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_service', 'Billing Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('billing_service', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('billing_service', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('billing_service', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name_ru',
            'description_ru:ntext',
            'archived:boolean',
            'default:boolean',
            'monthly:boolean',
            'unique:boolean',
            'currency_id',
            'enable_cost',
            'monthly_cost',
            'name_en',
            'description_en:ntext',
        ],
    ]) ?>

</div>
