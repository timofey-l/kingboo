<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingLogs */

$this->title = Yii::t('billing-logs', 'Create Billing Logs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing-logs', 'Billing Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-logs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
