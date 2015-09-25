<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BillingPaysYandex */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_pays_yandex', 'Billing Pays Yandexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-pays-yandex-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('billing_pays_yandex', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('billing_pays_yandex', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('billing_pays_yandex', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'invoiceId',
            'payed:boolean',
            'checked:boolean',
            'check_post_dump:ntext',
            'avisio_post_dump:ntext',
            'billing_invoice_id',
        ],
    ]) ?>

</div>
