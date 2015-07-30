<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PayLog */

$this->title = Yii::t('backend_paylog', 'Create Pay Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_paylog', 'Pay Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
