<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingIncome */

$this->title = Yii::t('billing_income', 'Create Billing Income');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_income', 'Billing Incomes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-income-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
