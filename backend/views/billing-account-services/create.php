<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingAccountServices */

$this->title = Yii::t('billing_account_services', 'Create Billing Account Services');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_account_services', 'Billing Account Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-account-services-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
