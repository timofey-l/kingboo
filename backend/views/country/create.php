<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Country */

$this->title = Yii::t('backend_models', 'Create {modelClass}', [
    'modelClass' => 'Country',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
