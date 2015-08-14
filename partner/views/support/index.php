<?php

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = \Yii::t('support', 'Support');

$this->params['breadcrumbs'][] = \Yii::t('partner_support', 'Support messages');

$tickets = $dataProvider->getModels();

?>

<?php if (!$tickets): ?>
    <?= \yii\helpers\Html::a(
        '<i class="fa fa-comments-o"></i> ' . \Yii::t('partner_support', 'Start new dialog'),
        ['create'],
        ['class' => 'btn btn-success']) ?>
    <br/>
    <br/>
    <div class="callout callout-info">
        <h4><i class="icon fa fa-info"></i> <?= \Yii::t('partner_support', 'Dialogs are absent') ?></h4>
        <?= \Yii::t('partner_support', 'Press upper button to start dialog.') ?>
    </div>
<?php endif; ?>
<?php if ($tickets): ?>
    <div class="row">
        <div class="col-md-12">
            <?= \yii\helpers\Html::a(
                '<i class="fa fa-comments-o"></i> ' . \Yii::t('partner_support', 'Start new dialog'),
                ['create'],
                ['class' => 'btn btn-app bg-green']) ?>
        </div>
    </div>
    <br/><div class="row">
    <div class="col-md-12">
        <?php foreach ($tickets as $index => $ticket): ?>
            <?php /** @var \common\models\SupportMessage $ticket */ ?>
            <div class="box box-<?= $ticket->newMessages ? "warning" : "default" ?>">
                <div class="box-header with-border">
                    <div class="box-title">
                        <?= \yii\helpers\Html::a(\yii\helpers\Html::encode($ticket->title), ['thread', 'id' => $ticket->id]) ?>
                    </div>
                    <span class="small text-muted pull-right">
                        <?= (new \DateTime($ticket->created_at))->format(\Yii::t('partner_support', 'd/m/Y H:i:s')) ?>
                    </span>
                    <?php if ($ticket->newMessages): ?>
                        <span class="pull-right">&nbsp;</span>
                        <span class="label label-warning pull-right"><?= $ticket->newMessages ?></span>
                    <?php endif; ?>
                </div>
                <div class="box-body">
                    <?= \yii\helpers\StringHelper::truncateWords(\yii\helpers\Html::encode($ticket->text), 30) ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<?= \yii\widgets\LinkPager::widget([
    'pagination' => $dataProvider->getPagination(),
]) ?>
<?php endif; ?>
