<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BillingService */

$this->title = Yii::t('billing_service', 'Update {modelClass}: ', [
    'modelClass' => 'Billing Service',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_service', 'Billing Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('billing_service', 'Update');
?>
<div class="billing-service-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
