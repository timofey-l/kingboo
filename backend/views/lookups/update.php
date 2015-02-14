<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LookupField */
/* @var $langs Array */

$this->title = Yii::t('backend_models', 'Update {modelClass}: ', [
    'modelClass' => 'Lookup Field',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Lookup Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend_models', 'Update');
?>
<div class="lookup-field-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'langs' => $langs,
    ]) ?>

</div>
