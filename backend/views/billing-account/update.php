<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BillingAccount */

$this->title = Yii::t('billing_account', 'Update {modelClass}: ', [
    'modelClass' => 'Billing Account',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_account', 'Billing Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('billing_account', 'Update');
?>
<div class="billing-account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
