<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingExpense */

$this->title = Yii::t('billing_expense', 'Create Billing Expense');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_expense', 'Billing Expenses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-expense-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
