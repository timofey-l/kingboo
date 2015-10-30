<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CallsStats */

$this->title = Yii::t('backend', 'Create Calls Stats');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Calls Stats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calls-stats-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
