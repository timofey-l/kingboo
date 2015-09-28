<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PayMethod */

$this->title = Yii::t('pay-method', 'Create Pay Method');
$this->params['breadcrumbs'][] = ['label' => Yii::t('pay-method', 'Pay Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-method-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
