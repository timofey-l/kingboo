<?php
/* @var $this yii\web\View */
/* @var $room \common\models\Room */
/* @var $hotel \common\models\Hotel */
/* @var $bookingParams \frontend\models\BookingParams */
/* @var $orderForm \frontend\models\OrderForm */

$l = \common\models\Lang::$current->url;
$bower = \Yii::$app->assetManager->getPublishedUrl('@bower');

//Подключаем colorbox
$this->registerJsFile($bower . '/colorbox/jquery.colorbox-min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($bower . '/colorbox/i18n/jquery.colorbox-' . $l . '.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile($bower . '/colorbox/example1/colorbox.css', [], 'colorbox');

// bootstrap transition.js plugin
$this->registerJsFile($bower . "/bootstrap/js/transition.js", ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]);
$this->registerJsFile($bower . "/bootstrap/js/carousel.js", ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]);
$this->registerJsFile($bower . "/bootstrap/js/tooltip.js", ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]);

$this->registerJsFile('/js/order.js', ['depends' => [\yii\web\YiiAsset::className()]]);

// инициализация подсказок
$this->registerJs(" $('[data-toggle=\"tooltip\"]').tooltip({html: true})", \yii\web\View::POS_READY, 'tooltip init');

$this->title = \Yii::t('frontend', 'Room booking');
?>

	<div class="row">
		<div class="col-sm-4">
			<div id="hotel-images-carousel" class="carousel slide" data-ride="carousel">
				<!-- Wrapper for slides -->
				<div class="carousel-inner" role="listbox">
					<?php foreach ($hotel->images as $i => $image): ?>
						<div class="item <?= $i == 0 ? "active" : '' ?>">
							<img src="<?= $image->getThumbUploadUrl('image', 'bigPreview') ?>" alt="">
							<a class="colorbox" href="<?= $image->getUploadUrl('image') ?>" rel="hotel-images"
							   style="display:none;"></a>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- Controls -->
				<a class="left carousel-control" href="#hotel-images-carousel" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control" href="#hotel-images-carousel" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
			<?php if (count($hotel->images) > 0): ?>
				<a class="btn btn-link pull-right" onclick="$('.item.active>a[rel=hotel-images]').click();">
					<span class="glyphicon glyphicon glyphicon-camera" aria-hidden="true"></span>
					<?= \Yii::t('frontend', '{n, plural, =1{photo} other{# photos}}', ['n' => count($hotel->images)]) ?>
				</a>
			<?php endif; ?>
		</div>
		<div class="col-sm-8">
			<span class="hotel_stars">
			<?php foreach (range(1, 5) as $i): ?>
				<?php if ($i <= $hotel->category) : ?>
					&#x2605;
				<?php else: ?>
					&#x2606;
				<?php endif ?>
			<?php endforeach; ?>
			</span>

			<h3>
				<?= $hotel->{'title_' . $l} ?>
			</h3>

			<div>
				<?= $hotel->address ?>
			</div>
			<div class="hotel-facilities">
				<?php foreach ($hotel->facilities as $f): ?>
					<span class="label label-default">
						<?= $f->{'name_' . $l} ?>
					</span>
				<?php endforeach ?>
			</div>
		</div>
	</div>

<?php $form = \yii\bootstrap\ActiveForm::begin() ?>
	<?= $form->field($orderForm, 'dateFrom', ['template' => '{input}'])->hiddenInput() ?>
	<?= $form->field($orderForm, 'dateTo', ['template' => '{input}'])->hiddenInput() ?>
	<div class="row">
		<div class="col-sm-4">
			<!--срок проживания-->
			<div class="panel panel-success order-info-panel">
				<div class="panel-heading">
					<?= \Yii::t('frontend', 'Order information') ?>
				</div>
				<div class="panel-body">
					<span class="dates-range">
						<span class=""><?= (new DateTime($bookingParams->dateFrom))->format('d/m') ?></span>&nbsp;&mdash;&nbsp;<span
							class=""><?= (new DateTime($bookingParams->dateTo))->format('d/m/Y') ?></span>
					</span>
					<br/>
					<?= \Yii::t('frontend', 'Nights: #', [
						'n' => (int)(new DateTime($bookingParams->dateTo))->diff(new DateTime($bookingParams->dateFrom))->days
					]) ?>
					<br/>
					<?= \Yii::t('frontend', 'Guests: #', [
						'n' => $bookingParams->adults + $bookingParams->children + $bookingParams->kids
					]) ?>
					<br/>
					<span class="sum text-success">
					<?= $price ?>
						&nbsp;<?= $hotel->currency->symbol != "" ? $hotel->currency->symbol : $hotel->currency->code ?>
					</span>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?= \Yii::t('frontend', 'Contact information') ?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-7">
							<?= $form->field($orderForm, 'email', [
								'inputOptions' => [
									'type'           => 'email',
									'data-toggle'    => "tooltip",
									'data-placement' => "bottom",
									'title'          => \Yii::t('frontend', 'Enter your email. <br/>This email will be used to contact you and confirm booking.'),
								]]) ?>
						</div>
						<div class="col-sm-5">
							<?= $form->field($orderForm, 'phone') ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<?= $form->field($orderForm, 'name') ?>
						</div>
						<div class="col-sm-6">
							<?= $form->field($orderForm, 'surname') ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php foreach ($orderForm->items as $index => $item): ?>
	<div class="panel panel-primary panel-room">
		<div class="panel-heading">
			<?= $item['room']->{'title_' . $l} ?>
			<a class="pull-right visible-xs"
			   onclick="$('.item.active>a[rel=room-images<?= $item['room']->id ?>]').click();">
				<span class="glyphicon glyphicon glyphicon-camera" aria-hidden="true"></span>
				<?= \Yii::t('frontend', '{n, plural, =1{photo} other{# photos}}', ['n' => count($item->room->images)]) ?>
			</a>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-3">
					<div id="room-images-carousel<?= $item->room->id ?>" class="carousel slide hidden-xs"
					     data-ride="carousel">
						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<?php foreach ($item->room->images as $i => $image): ?>
								<div class="item <?= $i == 0 ? "active" : '' ?>">
									<img src="<?= $image->getThumbUploadUrl('image', 'preview') ?>" alt="">
									<a class="colorbox" href="<?= $image->getUploadUrl('image') ?>"
									   rel="room-images<?= $item->room->id ?>"
									   style="display:none;"></a>
								</div>
							<?php endforeach; ?>
						</div>
						<!-- Controls -->
						<a class="left carousel-control" href="#room-images-carousel<?= $item->room->id ?>"
						   role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control" href="#room-images-carousel<?= $item->room->id ?>"
						   role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
					<a class="btn btn-link pull-right hidden-xs"
					   onclick="$('.item.active>a[rel=room-images<?= $item['room']->id ?>]').click();">
						<span class="glyphicon glyphicon glyphicon-camera" aria-hidden="true"></span>
						<?= \Yii::t('frontend', '{n, plural, =1{photo} other{# photos}}', ['n' => count($item->room->images)]) ?>
					</a>
				</div>
				<div class="col-sm-9">
					<div class="room-description">
						<?= $item['room']->{'description_' . $l} ?>
					</div>
					<?= $form->field($item, 'roomId', [
						'inputOptions' => [
							'name' => 'OrderForm[items]['.$index.'][roomId]'
						],
						'template' => '{input}',
					])->hiddenInput() ?>
					<div class="row room-form">
						<div class="col-sm-6">
							<?= $form->field($item, 'name', [
								'inputOptions' => [
									'name' => 'OrderForm[items]['.$index.'][name]'
								]
							]) ?>
						</div>
						<div class="col-sm-6">
							<?= $form->field($item, 'surname', [
								'inputOptions' => [
									'name' => 'OrderForm[items]['.$index.'][surname]'
								]
							]) ?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach ?>

<div class="row">
	<div class="col-md-12">
		<?= \yii\helpers\Html::submitButton(\Yii::t('frontend', 'Go to payment'), [
			'class' => 'btn btn-primary btn-lg pull-right'
		]) ?>
	</div>
</div>

<?php \yii\bootstrap\ActiveForm::end(); ?>