<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BillingPaysYandex */

$this->title = Yii::t('billing_pays_yandex', 'Update {modelClass}: ', [
    'modelClass' => 'Billing Pays Yandex',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_pays_yandex', 'Billing Pays Yandexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('billing_pays_yandex', 'Update');
?>
<div class="billing-pays-yandex-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
