<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingService */

$this->title = Yii::t('billing_service', 'Create Billing Service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_service', 'Billing Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-service-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
