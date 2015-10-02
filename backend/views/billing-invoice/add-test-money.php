<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BillingInvoice */

$this->title = Yii::t('billing_invoice', 'Create test payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_invoice', 'Billing Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-warning" role="alert"><strong>Attantion!</strong> It&acute;s account ID. <font color="red">Not partner!</font></div>

	<div class="billing-invoice-form">

    	<?php $form = ActiveForm::begin(); ?>

    	<?= $form->field($model, 'account_id')->textInput() ?>

    	<?= $form->field($model, 'sum')->textInput() ?>

    	<div class="form-group">
        	<?= Html::submitButton(Yii::t('billing_invoice', 'Add'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    	</div>

    	<?php ActiveForm::end(); ?>

</div>

</div>
