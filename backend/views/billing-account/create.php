<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingAccount */

$this->title = Yii::t('billing_account', 'Create Billing Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_account', 'Billing Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
