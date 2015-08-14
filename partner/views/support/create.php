<?php

/* @var $this yii\web\View */
/** @var \common\models\SupportMessage $model */

$this->title = \Yii::t('support', 'New message to support');

$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_support', 'Support messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = \Yii::t('partner_support', 'New message');
?>
<?= \yii\helpers\Html::a(
    '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_support', 'Back to list of messages'),
    ['index'],
    ['class' => 'btn btn-default']) ?>

<?php $form = \yii\bootstrap\ActiveForm::begin() ?>
<?= $form->field($model, 'hash')->hiddenInput()->label(false); ?>
<div class="row">
    <div class="col-lg-6 col-md-10 col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('support', 'Contacting сustomer support') ?></h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'title'); ?>
                <?= $form->field($model, 'text')->textarea(['rows' => 10])?>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <?= \yii\helpers\Html::a(\Yii::t('support', 'Cancel'), ['/support'], [
                        'class' => 'btn btn-default'
                    ]) ?>
                    <button class="btn btn-primary"><?= \Yii::t('support', 'Send') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end() ?>
