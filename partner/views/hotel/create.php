<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$this->title = Yii::t('hotels', 'Create {modelClass}', [
    'modelClass' => 'Hotel',
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-create">
    <?= \yii\helpers\Html::a(
        '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_hotel', 'Back to hotel view'),
        ['view', 'id' => $hotel->id],
        ['class' => 'btn btn-default']) ?>
    <br/>
    <br/>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
