<?php

/* @var $this \yii\web\View */
/* @var $model \common\models\SupportMessage */
/* @var $answers \common\models\SupportMessage[] */
/* @var $newMessage \common\models\SupportMessage */

$this->title = \Yii::t('support', 'Support message #{n}', ['n' => $model->id]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_support', 'Support messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = \Yii::t('partner_support', 'Message {n}', ['n' => $model->id]);
?>

<div class="box box-solid box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= \yii\helpers\Html::encode($model->title) ?></h3>

        <div class="box-tools pull-right">
            <small><?= (new DateTime($model->created_at))->format(\Yii::t('support', 'd/m/Y H:i:s')) ?></small>
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?= \yii\helpers\Html::encode($model->text) ?>
    </div>
</div>

<?php foreach ($answers as $answer): ?>
    <div class="row">
        <div class="col-xs-11 col-sm-10 <?= $answer->author ? "" : "col-xs-offset-1 col-sm-offset-2" ?>">
            <div class="box box-<?= $answer->unread ? "warning box-solid" : "default" ?>" id="id<?= $answer->id ?>">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?php if (!$answer->author): ?>
                            <i class="fa fa-group"></i>  <?= \Yii::t('support', 'Support team') ?>
                        <?php else: ?>
                            <?= \Yii::t('support', 'Your message') ?>
                        <?php endif; ?>
                    </h3>

                    <div class="box-tools pull-right">
                        <small><?= (new DateTime($answer->created_at))->format(\Yii::t('support', 'd/m/Y H:i:s')) ?></small>
                    </div>
                </div>
                <div class="box-body">
                    <?= \yii\helpers\Html::encode($answer->text) ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php $form = \yii\bootstrap\ActiveForm::begin() ?>
<?= $form->field($newMessage, 'hash')->hiddenInput()->label(false); ?>
<div class="box box-solid box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= \Yii::t('support', 'Your message') ?></h3>
    </div>
    <div class="box-body">
        <?= $form->field($newMessage, 'text')->textarea(['rows' => 5])->label(false) ?>
    </div>
    <div class="box-footer">
        <div class="pull-right">
            <?= \yii\helpers\Html::a(\Yii::t('support', 'All messages'), ['/support'], [
                'class' => 'btn btn-default',
            ]) ?>
            <button class="btn btn-primary"><?= \Yii::t('support', 'Send') ?></button>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
