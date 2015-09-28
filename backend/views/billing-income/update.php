<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BillingIncome */

$this->title = Yii::t('billing_income', 'Update {modelClass}: ', [
    'modelClass' => 'Billing Income',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_income', 'Billing Incomes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('billing_income', 'Update');
?>
<div class="billing-income-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
