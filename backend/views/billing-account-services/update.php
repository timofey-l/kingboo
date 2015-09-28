<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BillingAccountServices */

$this->title = Yii::t('billing_account_services', 'Update {modelClass}: ', [
    'modelClass' => 'Billing Account Services',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_account_services', 'Billing Account Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('billing_account_services', 'Update');
?>
<div class="billing-account-services-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
