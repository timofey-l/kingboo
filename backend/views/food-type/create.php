<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\FoodType */

$this->title = Yii::t('backend_models', 'Create {modelClass}', [
    'modelClass' => 'Food Type',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Food Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="food-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
