<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PayMethod */

$this->title = Yii::t('pay-method', 'Update {modelClass}: ', [
    'modelClass' => 'Pay Method',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('pay-method', 'Pay Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('pay-method', 'Update');
?>
<div class="pay-method-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
