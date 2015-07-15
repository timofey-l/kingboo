<?php

use yii\helpers\Html;

$l = \common\models\Lang::$current->url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('partner_widget', 'Widgets');
$this->params['breadcrumbs'][] = ['label' => $hotel->{'title_' . $l}, 'url' => ['hotel/view', 'id' => $hotel->id]];
$this->params['breadcrumbs'][] = $this->title;

$widgets = $dataProvider->getModels();

?>
<div class="widget-index">
    <?= \yii\helpers\Html::a(
        '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_hotel', 'Back to hotel view'),
        ['view', 'id' => $hotel->id],
        ['class' => 'btn btn-default']) ?>
        <?php if (!$widgets): ?>
            <?= Html::a('<i class="fa fa-plus-circle"></i> ' . Yii::t('partner_widget', 'Create Widget'), ['widget-create', 'id'=>$hotel_id], ['class' => 'btn btn-success']) ?>
            <br/>
            <br/>
        <div class="alert alert-info alert-dismissable col-sm-8 col-md-6">
            <h4><i class="icon fa fa-info"></i> <?= \Yii::t('partner_widget', 'Widgets are absent') ?></h4>
            <?= \Yii::t('partner_widget', 'Press upper button to create new widget.') ?>
        </div>
    <?php endif; ?>


    <?php if ($widgets): ?>
		<?= Html::a('<i class="fa fa-plus-circle"></i> ' . Yii::t('partner_widget', 'Create Widget'), ['widget-create', 'id' => $hotel_id], ['class' => 'btn btn-success']) ?>
        <br/>
        <br/>
	<div class="row">
		<?php foreach ($widgets as $widget): ?>
			<div class="col-sm-6">
				<div class="box box-default">
					<div class="box-header with-border">
						<span class="box-title">
							<?= Html::encode($widget->comment) ?>
						</span>
						<?= Html::a(Yii::t('partner_widget', 'Delete'), ['delete-widget', 'id' => $widget->id], [
							'class' => 'btn btn-danger btn-xs pull-right',
							'data'  => [
								'confirm' => Yii::t('partner_widget', 'Are you sure you want to delete this item?'),
//								'method'  => 'post',
							],
						]) ?>
						<span class="pull-right">&nbsp;</span>
						<?= Html::a(Yii::t('partner_widget', 'Update'), ['update-widget', 'id' => $widget->id], [
							'class' => 'btn btn-primary btn-xs pull-right',
						]) ?>

					</div>
					<div class="box-body">
						<b><?= \Yii::t('partner_widget','Widget id:') ?></b>
						<code>
						<?= Html::encode($widget->hash_code)?>
						</code>
						<br/>
						<br/>
						<p>
							<?= \Yii::t('partner_widget','Copy this code and insert in the place where widget should be located.') ?>
						</p>
						<?= Html::textarea('widget' . $widget->id, '<script type="application/javascript" src="http://partner.king-boo.com/widget/js/' . $widget->hash_code . '"></script>',[
							'class' => 'form-control',
							'style' => 'font-family: Menlo, Monaco, Consolas, "Courier New", monospace;',
							'rows' => 5,
						]) ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
    <?php endif; ?>


</div>
