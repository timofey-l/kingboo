<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Widget */
/* @var $form yii\widgets\ActiveForm */

$l = \common\models\Lang::$current->url;

// подключаем colorpicker для параметров
$bd = \Yii::$app->assetManager->getPublishedUrl('@bower');

//$this->registerJsFile($bd.'/jquery-minicolors/jquery.minicolors.min.js', ['depends' => \partner\assets\AppAsset::className()]);
//$this->registerJs("$('.minicolors').minicolors();");
//$this->registerCssFile($bd.'/jquery-minicolors/jquery.minicolors.css', ['depends' => \partner\assets\AppAsset::className()]);
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title"><?= \Yii::t('partner_widget', 'Common information') ?></h3>
			</div>
			<div class="box-body">
				<div class="widget-form">

					<div class="form-group">
						<?= $form->field($model, 'comment')->textarea(['rows' => 5]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="box box-default" id="paramsPanel">
			<div class="box-header with-border" >
				<h3 class="box-title"><?= \Yii::t('partner_widget','Options') ?></h3>
			</div>
			<div class="box-body">
					<?php foreach(\yii\helpers\Json::decode($model->params) as $paramName => $param): ?>
						<?php
							$inp_name = 'Widget[params]['.$paramName.'][value]';
							switch($param['type']){
							case "integer":
								?>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-8">
											<?= Html::label($param['title_' . $l], $inp_name, [
												'class' => 'control-label',
											]); ?>
										</div>
										<div class="col-sm-4">
											<?= Html::input('number', $inp_name, $param['value'],[
												'class' => 'form-control',
//												'id' => $inp_name,
											]); ?>
										</div>
									</div>
								</div>
								<?php break;
							case "color":
								?>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-8">
											<?= Html::label($param['title_' . $l], $inp_name, [
												'class' => 'control-label',
											]); ?>
										</div>
										<div class="col-sm-4">
											<?= Html::input('color', $inp_name, $param['value'],[
												'class' => 'form-control',
//												'id' => $inp_name,
											]); ?>
										</div>
									</div>
								</div>
								<?php break;
							case "boolean":
								?>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-8">
											<?= Html::label($param['title_' . $l], $inp_name, [
												'class' => 'control-label',
											]); ?>
										</div>
										<div class="col-sm-4">
											<?= Html::dropDownList($inp_name, $param['value'],[
												'0' => Yii::t('partner_widget', 'No'),
												'1' => Yii::t('partner_widget', 'Yes'),
											],[
												'class' => 'form-control',
//												'id' => $inp_name,
											]); ?>
										</div>
									</div>
								</div>
								<?php break;
						} ?>
					<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('partner_widget', 'Create') : Yii::t('partner_widget', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>



