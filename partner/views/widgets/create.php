<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Widget */

$this->title = Yii::t('partner_widget', 'Create Widget');
$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_widget', 'Widgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
