<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('partner_widget', 'Widgets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-index">

	<p>
		<?= Html::a('<i class="fa fa-plus-circle"></i> ' . Yii::t('partner_widget', 'Create Widget'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<hr style="border-color: #ccc;"/>
	<div class="row">
		<?php foreach ($dataProvider->getModels() as $widget): ?>
			<div class="col-sm-6">
				<div class="box box-default">
					<div class="box-header with-border">
						<span class="box-title">
							<?= Html::encode($widget->comment) ?>
						</span>
						<?= Html::a(Yii::t('partner_widget', 'Delete'), ['delete', 'id' => $widget->id], [
							'class' => 'btn btn-danger btn-xs pull-right',
							'data'  => [
								'confirm' => Yii::t('partner_widget', 'Are you sure you want to delete this item?'),
								'method'  => 'post',
							],
						]) ?>
						<span class="pull-right">&nbsp;</span>
						<?= Html::a(Yii::t('partner_widget', 'Update'), ['update', 'id' => $widget->id], [
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
						<?= Html::textarea('widget' . $widget->id, '<script type="application/javascript" src="http://partner.booking.local/widget/js/' . $widget->hash_code . '"></script>',[
							'class' => 'form-control',
							'style' => 'font-family: Menlo, Monaco, Consolas, "Courier New", monospace;',
							'rows' => 5,
						]) ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>


</div>
