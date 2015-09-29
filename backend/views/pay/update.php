<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pay */

$this->title = Yii::t('partner_pays', 'Update {modelClass}: ', [
    'modelClass' => 'Pay',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_pays', 'Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('partner_pays', 'Update');
?>
<div class="pay-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
