<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$this->title = Yii::t('hotels', 'Create {modelClass}', [
    'modelClass' => 'Hotel',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotels', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
