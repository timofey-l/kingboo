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

		<?= Html::a('<i class="fa fa-plus-circle"></i> ' . Yii::t('partner_widget', 'Create Widget'), ['widget-create', 'id' => $hotel_id], ['class' => 'btn btn-success']) ?>
        <br/>
        <br/>

    <?php if (!$widgets): ?>
    <div class="callout callout-info">
        <h4><i class="icon fa fa-info"></i> <?= \Yii::t('partner_widget', 'Widgets are absent') ?></h4>
        <?= \Yii::t('partner_widget', 'Press upper button to create new widget.') ?>
    </div>
   	<?php endif; ?>

	<div class="row">
    	<div class="col-xs-12">
        	<?= \Yii::t('partner_widget', '<p>If you already have personal website of your hotel (not in the <i>{site}</i>), you can install widget for online booking and payment on it. When widget installation is suggessfully complete, a special code is generated. All you need is to insert this code into HTML code of your site to the place where you want to locate the widget, and online payment system is ready to use!</p>', ['site' => \Yii::$app->params['mainDomainShort']]) ?>
    	</div>

    <?php if ($widgets): ?>
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
						<?= Html::textarea('widget' . $widget->id, '<script type="application/javascript" src="https://partner.king-boo.com/widget/js/' . $widget->hash_code . '"></script>',[
							'class' => 'form-control',
							'style' => 'font-family: Menlo, Monaco, Consolas, "Courier New", monospace;',
							'readonly' => 'readonly',
							'rows' => 5,
						]) ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
    <?php endif; ?>
	</div>


</div>
