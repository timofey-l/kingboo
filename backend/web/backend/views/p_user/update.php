<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model partner\models\PartnerUser */

$this->title = Yii::t('backend_models', 'Update {modelClass}: ', [
    'modelClass' => 'Partner User',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Partner Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend_models', 'Update');
?>
<div class="partner-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
