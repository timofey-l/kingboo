<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Pay */

$this->title = Yii::t('partner_pays', 'Create Pay');
$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_pays', 'Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
