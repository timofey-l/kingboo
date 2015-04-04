<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Currency */

$this->title = Yii::t('backend_models', 'Create {modelClass}', [
    'modelClass' => 'Currency',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="currency-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
