<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BillingExpense */

$this->title = Yii::t('billing_expense', 'Update {modelClass}: ', [
    'modelClass' => 'Billing Expense',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_expense', 'Billing Expenses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('billing_expense', 'Update');
?>
<div class="billing-expense-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
