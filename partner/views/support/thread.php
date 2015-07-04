<?php

/* @var $this \yii\web\View */
/* @var $model \common\models\SupportMessage */
/* @var $answers \common\models\SupportMessage[] */
/* @var $newMessage \common\models\SupportMessage */

$this->title = \Yii::t('support', 'Support message #{n}', ['n' => $model->id]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('partner_support', 'Support messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = \Yii::t('partner_support', 'Dialog #{n}', ['n' => $model->id]);
?>

<div>
<?= \yii\helpers\Html::a(
    '<i class="fa fa-arrow-left"></i> '.\Yii::t('partner_support', 'Back to all messages'),
    ['index'],
    ['class' => 'btn btn-default']) ?>

<?= \yii\helpers\Html::a(
    '<i class="fa fa-comments-o"></i> ' . \Yii::t('partner_support', 'Start new dialog'),
    ['create'],
    ['class' => 'btn btn-success pull-right']) ?>
</div>
<br/>
<div class="row" style="border-bottom: 1px solid #cccccc;">
    <div class="col-sm-8 col-sm-offset-3 col-xs-9 col-xs-offset-1">
        <span class="text-muted small"><?= \Yii::t('partner_support', 'Dialog #{n}', ['n' => $model->id]) ?></span>
        <br/>
        <span class="text-black"><strong><?= \yii\helpers\Html::encode($model->title) ?></strong></span>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-sm-7 col-sm-offset-3 col-xs-8 col-xs-offset-1">
        <div class="box box-default">
            <div class="box-body">
                <?= \yii\helpers\Html::encode($model->text) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-1 col-xs-2">
        <span class="small text-muted"><?= (new DateTime($model->created_at))->format(\Yii::t('partner_support', 'd/m/Y')) ?></span>
    </div>
</div>


<?php foreach ($answers as $answer): ?>
    <div class="row" id="id<?= $answer->id ?>">
        <?php if ($answer->author): ?>
            <div class="col-sm-7 col-sm-offset-3 col-xs-8 col-xs-offset-1">
                <div class="box box-default">
                    <div class="box-body">
                        <?= \yii\helpers\Html::encode($answer->text) ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-1 col-xs-2">
                <span
                    class="small text-muted"><?= (new DateTime($answer->created_at))->format(\Yii::t('partner_support', 'd/m/Y')) ?></span>
            </div>
        <?php else: ?>
            <div class="col-sm-7 col-xs-7">
                <div class="box box-<?= $answer->unread ? "warning" : "dafault" ?>">
                    <div class="box-body bg-warning">
                        <?= \yii\helpers\Html::encode($answer->text) ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-1 col-sm-offset-3 col-xs-1 col-xs-offset-2">
                <span
                    class="small text-muted"><?= (new DateTime($answer->created_at))->format(\Yii::t('partner_support', 'd/m/Y')) ?></span>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<div class="row">
    <div class="col-sm-offset-3 col-sm-9 col-lg-7 col-lg-offset-3">
        <?php $form = \yii\bootstrap\ActiveForm::begin() ?>
        <?= $form->field($newMessage, 'hash')->hiddenInput()->label(false); ?>
        <div class="box box-solid box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-comment"></i> <?= \Yii::t('support', 'Your message') ?></h3>
            </div>
            <div class="box-body">
                <?= $form->field($newMessage, 'text')->textarea(['rows' => 5])->label(false) ?>
            </div>
            <div class="box-footer">
                <div class="pull">
                    <?= \yii\helpers\Html::a('<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_support', 'Back to all messages'), ['/support'], [
                        'class' => 'btn btn-default',
                    ]) ?>
                    <button class="btn btn-primary pull-right"><?= \Yii::t('support', 'Send') ?></button>
                </div>
            </div>
        </div>
        <?php \yii\bootstrap\ActiveForm::end(); ?>
    </div>
</div>
