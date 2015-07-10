<?php

/* @var $this \yii\web\View */
/* @var $model \common\models\SupportMessage */
/* @var $answers \common\models\SupportMessage[] */
/* @var $newMessage \common\models\SupportMessage */

$this->title = \Yii::t('support', 'Support message #{n}', ['n' => $model->id]);

?>

<div class="panel panel-solid panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= \yii\helpers\Html::encode($model->title) ?>

            <div class="pull-right">
                <?= (new DateTime($model->created_at))->format(\Yii::t('support', 'd/m/Y H:i:s')) ?>
            </div>
        </h3>
    </div>
    <div class="panel-body">
        <?= \yii\helpers\Html::encode($model->text) ?>
    </div>
    <?php if ($model->unread_admin): ?>
        <div class="panel-footer">
            <?= \yii\helpers\Html::a(\Yii::t('support', 'Set as readed'), ['set-readed', 'id' => $model->id], [
                'class' => 'btn btn-warning',
            ]) ?>
        </div>
    <?php endif; ?>
</div>

<?php foreach ($answers as $answer): ?>
    <div class="row">
        <div class="col-xs-11 col-sm-10 <?= $answer->author ? "" : "col-xs-offset-1 col-sm-offset-2" ?>">
            <div class="panel panel-<?= $answer->unread_admin ? "warning panel-solid" : "default" ?>" id="id<?= $answer->id ?>">
                <div class="panel-heading with-border">
                    <h3 class="panel-title">
                        <?php if (!$answer->author): ?>
                            <i class="fa fa-group"></i>  <?= \Yii::t('support', 'Support team') ?>
                        <?php else: ?>
                            <?= \Yii::t('support', 'User message') ?>
                        <?php endif; ?>
                    <div class="panel-tools pull-right">
                        <small><?= (new DateTime($answer->created_at))->format(\Yii::t('support', 'd/m/Y H:i:s')) ?></small>
                    </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <?= \yii\helpers\Html::encode($answer->text) ?>
                </div>
                <?php if ($answer->unread_admin): ?>
                <div class="panel-footer">
                    <?= \yii\helpers\Html::a(\Yii::t('support', 'Set as readed'), ['set-readed', 'id' => $answer->id], [
                        'class' => 'btn btn-warning',
                    ]) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php $form = \yii\bootstrap\ActiveForm::begin() ?>
<?= $form->field($newMessage, 'hash')->hiddenInput()->label(false); ?>
<div class="panel panel-solid panel-default">
    <div class="panel-heading with-border">
        <h3 class="panel-title"><?= \Yii::t('support', 'Support message') ?></h3>
    </div>
    <div class="panel-body">
        <?= $form->field($newMessage, 'text')->textarea(['rows' => 5])->label(false) ?>
    </div>
    <div class="panel-footer">
        <?= \yii\helpers\Html::a(\Yii::t('support', 'All messages'), ['/support'], [
            'class' => 'btn btn-default',
        ]) ?>
        <div class="pull-right">
            <button class="btn btn-primary"><?= \Yii::t('support', 'Send') ?></button>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
