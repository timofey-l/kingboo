<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Country */

$this->title = Yii::t('backend_models', 'Update {modelClass}: ', [
    'modelClass' => 'Country',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend_models', 'Update');
?>
<div class="country-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
