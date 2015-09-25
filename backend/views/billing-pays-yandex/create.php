<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BillingPaysYandex */

$this->title = Yii::t('billing_pays_yandex', 'Create Billing Pays Yandex');
$this->params['breadcrumbs'][] = ['label' => Yii::t('billing_pays_yandex', 'Billing Pays Yandexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-pays-yandex-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
