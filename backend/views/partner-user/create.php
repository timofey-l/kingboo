<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model partner\models\PartnerUser */

$this->title = Yii::t('backend_models', 'Create {modelClass}', [
    'modelClass' => 'Partner User',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Partner Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_create_form', [
        'model' => $model,
    ]) ?>

</div>
