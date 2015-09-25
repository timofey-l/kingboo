<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingInvoice */

$this->title = Yii::t('billing_invoice', 'Create Billing Invoice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_invoice', 'Billing Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
