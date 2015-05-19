<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Widget */

$this->title = Yii::t('partner_widget', 'Update {modelClass}: ', [
    'modelClass' => 'Widget',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_widget', 'Widgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('partner_widget', 'Update');
?>
<div class="widget-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
