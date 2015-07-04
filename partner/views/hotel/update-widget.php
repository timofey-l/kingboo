<?php

use yii\helpers\Html;

$l = \common\models\Lang::$current->url;
/* @var $this yii\web\View */
/* @var $model common\models\Widget */

$this->title = Yii::t('partner_widget', 'Widget #{n} edit', ['n' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => $hotel->{'title_' . $l}, 'url' => ['hotel/view', 'id' => $hotel->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_widget', 'Widgets'), 'url' => ['widgets', 'id' => $hotel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \yii\helpers\Html::a(
    '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_widget', 'Back to widgets'),
    ['index'],
    ['class' => 'btn btn-default']) ?>
<br/>
<br/>
<div class="widget-update">

    <?= $this->render('_widget_form', [
        'model' => $model,
    ]) ?>

</div>
