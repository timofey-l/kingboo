<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model partner\models\PartnerUser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend_models', 'Partner Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-user-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a(Yii::t('backend_models', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a(Yii::t('backend_models', 'Delete'), ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data'  => [
				'confirm' => Yii::t('backend_models', 'Are you sure you want to delete this item?'),
				'method'  => 'post',
			],
		]) ?>
	</p>

	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'id',
			'username',
			'auth_key',
			'password_hash',
			'password_reset_token',
			'email:email',
			'checked:boolean',
			'status',
			'created_at',
			'updated_at',
			'group',
			'shopId',
			'shopPassword',
			'scid',
			'lang',
		],
	]) ?>

</div>
