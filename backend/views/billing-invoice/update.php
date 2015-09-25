<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BillingInvoice */

$this->title = Yii::t('billing_invoice', 'Update {modelClass}: ', [
    'modelClass' => 'Billing Invoice',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_invoice', 'Billing Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('billing_invoice', 'Update');
?>
<div class="billing-invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
