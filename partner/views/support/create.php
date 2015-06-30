<?php

/* @var $this yii\web\View */
/** @var \common\models\SupportMessage $model */

$this->title = \Yii::t('support', 'New message to support');

?>

<?php $form = \yii\bootstrap\ActiveForm::begin() ?>
<?= $form->field($model, 'hash')->hiddenInput()->label(false); ?>
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('support', 'Message text') ?></h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'text')->textarea(['rows' => 10])->label(false) ?>
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
