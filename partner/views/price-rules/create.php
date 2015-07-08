<?php

/** @var \common\models\PriceRules $model */
/** @var \common\models\Room[] $rooms */
/** @var \yii\web\View $this */

$this->title = \Yii::t('partner_pricerules', 'Create {price_rule}', [
    'price_rule' => strtolower($model->title),
]);

$this->params['breadcrumbs'][] = ['url' => ['index'], 'label' => \Yii::t('partner_pricerules', 'Price rules')];
$this->params['breadcrumbs'][] = $this->title;


?>
<?= \yii\helpers\Html::a(
    '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_pricerules', 'Back to price rules'),
    ['index'],
    ['class' => 'btn btn-default']) ?>
<br/>
<br/>
<?= $this->render('_form' . $model->type, ['model' => $model, 'hotels' => $hotels]) ?>


