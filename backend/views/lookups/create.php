<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\LookupField */
/* @var $langs Array */

$this->title = Yii::t('backend_models', 'Create {modelClass}', [
    'modelClass' => 'Lookup Field',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Lookup Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lookup-field-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'langs' => $langs,
    ]) ?>

</div>
