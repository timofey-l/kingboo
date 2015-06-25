<?php

/** @var \common\models\PriceRules $model */
/** @var \yii\web\View $this */

$this->title = \Yii::t('partner_pricerules', 'Create {price_rule}', [
    'price_rule' => mb_strtolower($model->title),
]);

?>

<?= $this->render('_form' . $model->type, ['model' => $model]) ?>


