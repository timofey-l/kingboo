<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */

$this->title = Yii::t('backend_order', 'Create Order Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_order', 'Order Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
