<?php
/* @var $this yii\web\View */
use yii\bootstrap\BootstrapAsset;
use yii\helpers\Html;

/* @var $room \common\models\Room */
/* @var $hotel \common\models\Hotel */
/* @var $bookingParams \frontend\models\BookingParams */
/* @var $orderForm \common\models\Order */

$l = \common\models\Lang::$current->url;
$assetManager = \Yii::$app->assetManager;
//$bower = \Yii::$app->assetManager->getPublishedUrl('@bower');

$this->params['embedded'] = $embedded;
$this->params['no_desc'] = $no_desc;

$this->registerCss($hotel->css, [
	'depends' => BootstrapAsset::className(),
]);

$fa = $assetManager->publish('@bower/fontawesome')[1];
$this->registerCssFile($fa.'/css/font-awesome.min.css');

//Подключаем colorbox
$colorbox = $assetManager->publish('@bower/colorbox')[1];
$this->registerJsFile($colorbox . '/jquery.colorbox-min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($colorbox . '/i18n/jquery.colorbox-' . ($l == 'en' ? 'uk' : $l) . '.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile($colorbox . '/example1/colorbox.css', [], 'colorbox');

// bootstrap transition.js plugin
$bootstrap_js = $assetManager->publish('@bower/bootstrap/js')[1];
$this->registerJsFile($bootstrap_js . "/transition.js", ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]);
$this->registerJsFile($bootstrap_js . "/carousel.js", ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]);
$this->registerJsFile($bootstrap_js . "/tooltip.js", ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]);

$this->registerJsFile('/js/order.js', ['depends' => [\yii\web\YiiAsset::className()]]);

// инициализация подсказок
$this->registerJs(" $('[data-toggle=\"tooltip\"]').tooltip({html: true})", \yii\web\View::POS_READY, 'tooltip init');

$this->registerJs("
window.inputBlur = function(e) {
    switch (e.name) {
        case 'Order[contact_address]':
            if (!$('#orderitem-guest_address').val()) {
                $('#orderitem-guest_address').val($(e).val());
            }
            break;
        case 'Order[contact_name]':
            if (!$('#orderitem-guest_name').val()) {
                $('#orderitem-guest_name').val($(e).val());
            }
            break;
        case 'Order[contact_surname]':
            if (!$('#orderitem-guest_surname').val()) {
                $('#orderitem-guest_surname').val($(e).val());
            }
            break;
    }
}
");

// опции бронирования
$this->registerJs("
    $('.orderOptions > div input[type=checkbox]').on('change', function(){
        if ($(this).prop('disabled')) return;
        var parent = $(this).parent().parent().parent().parent();
        var chechbox = this;
        if ($(this).is(':checked')) {
            var disable = {};
            $.each($(this).data('disable').split(' '), function(i, el){
                disable[el] = true;
            });
            parent.find('input[type=checkbox]').each(function(index, el){
                if (chechbox == el) return;
                $.each($(el).data('disable').split(' '), function(i, elem){
                    if ($(el).is('checked')) {
                        disable[elem] = disable[elem] && true;
                    }
                });
            });
            $.each(disable, function(i, el){
                if (el) {
                    $(i).prop('disabled', true);
                    $(i).prop('checked', false);
                }
            });
        } else {
            var enable = {};
            $.each($(this).data('disable').split(' '), function(i, el){
                enable[el] = true;
            });
            parent.find('input[type=checkbox]').each(function(index, el){
                if (chechbox == el) return;
                $.each($(el).data('disable').split(' '), function(i, elem){
                    if ($(el).is(':checked')) {
                        enable[elem] = enable[elem] && false;
                    }
                });
            });
            $.each(enable, function(i, el){
                if (el) {
                    $(i).prop('disabled', false);
                }
            });
        }
    });
");

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
				<i class="fa fa-map-marker"></i> <?= $hotel->address ?>
                <br>
                <i class="fa fa-mobile"></i> <span class="phone"><?= Html::encode($hotel->contact_phone) ?></span>
				<br>
                <i class="fa fa-envelope-o"></i> <a class="email" href="mailto:<?= $hotel->contact_email ?>"><?= Html::encode($hotel->contact_email) ?></a>
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
	<?= $form->field($orderForm, 'hotel_id', ['template' => '{input}'])->hiddenInput() ?>
	<div class="row">
		<div class="col-sm-4">
			<!--срок проживания-->
			<div class="panel panel-success order-info-panel">
				<div class="panel-heading">
					<?= \Yii::t('frontend', 'Order information') ?>
				</div>
				<div class="panel-body">
					<span class="dates-range">
						<span class=""><?= (new DateTime($bookingParams->dateFrom))->format('d/m') ?></span>&nbsp;&mdash;&nbsp;<span class=""><?= (new DateTime($bookingParams->dateTo))->format('d/m/Y') ?></span>
					</span>
					<br/>
					<?= \Yii::t('frontend', 'Nights: {n}', [
						'n' => (int)(new DateTime($bookingParams->dateTo))->diff(new DateTime($bookingParams->dateFrom))->days
					]) ?>
					<br/>
					<?= \Yii::t('frontend', 'Guests: {n}', [
						'n' => $bookingParams->adults + $bookingParams->children + $bookingParams->kids
					]) ?>
					<br/>
					<span class="sum text-success">
					<?= $price ?>
						&nbsp;<?= $hotel->currency->symbol != "" ? $hotel->currency->symbol : $hotel->currency->code ?>
					</span>
				</div>
                <div class="panel-footer">
                    <?= $form->field($orderForm, 'code')->hint(\Yii::t('frontend', '* If you have')) ?>
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
							<?= $form->field($orderForm, 'contact_email', [
								'inputOptions' => [
									'type'           => 'email',
									'data-toggle'    => "tooltip",
									'data-placement' => "bottom",
									'title'          => \Yii::t('frontend', 'Enter your email. <br/>This email will be used to contact you and confirm booking.'),
								]]) ?>
						</div>
						<div class="col-sm-5">
							<?= $form->field($orderForm, 'contact_phone') ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2">
							<?= $form->field($orderForm, 'contact_address', [
                                'inputOptions' => [
                                    'onblur' => "window.inputBlur(this)"
                                ]
                            ])
								->dropDownList(
									\yii\helpers\ArrayHelper::merge(['' => ''], \common\components\ListAddressType::getSelectOptions())
								) ?>
						</div>
						<div class="col-sm-5">
							<?= $form->field($orderForm, 'contact_name', [
                                'inputOptions' => [
                                    'onblur' => "window.inputBlur(this)"
                                ]
                            ]) ?>
						</div>
						<div class="col-sm-5">
							<?= $form->field($orderForm, 'contact_surname', [
                                'inputOptions' => [
                                    'onblur' => "window.inputBlur(this)"
                                ]
                            ]) ?>
						</div>
					</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($orderForm, 'additional_info')->textarea([
                                'rows' => 3,
                            ]) ?>
                        </div>
                    </div>

                </div>
			</div>
		</div>
	</div>

<?php foreach ($items as $index => $item): $item_room = \common\models\Room::findOne($item['room_id']); ?>
	<div class="panel panel-primary panel-room">
		<div class="panel-heading">
			<?= $item_room->{'title_' . $l} ?>
			<a class="pull-right visible-xs"
			   onclick="$('.item.active>a[rel=room-images<?= $item_room->id ?>]').click();">
				<span class="glyphicon glyphicon glyphicon-camera" aria-hidden="true"></span>
				<?= \Yii::t('frontend', '{n, plural, =1{photo} other{# photos}}', ['n' => count($item_room->images)]) ?>
			</a>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-3">
					<div id="room-images-carousel<?= $item_room->id ?>" class="carousel slide hidden-xs"
					     data-ride="carousel">
						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<?php foreach ($item_room->images as $i => $image): ?>
								<div class="item <?= $i == 0 ? "active" : '' ?>">
									<img src="<?= $image->getThumbUploadUrl('image', 'preview') ?>" alt="">
									<a class="colorbox" href="<?= $image->getUploadUrl('image') ?>"
									   rel="room-images<?= $item_room->id ?>"
									   style="display:none;"></a>
								</div>
							<?php endforeach; ?>
						</div>
						<!-- Controls -->
						<a class="left carousel-control" href="#room-images-carousel<?= $item_room->id ?>"
						   role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control" href="#room-images-carousel<?= $item_room->id ?>"
						   role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
					<a class="btn btn-link pull-right hidden-xs"
					   onclick="$('.item.active>a[rel=room-images<?= $item_room->id ?>]').click();">
						<span class="glyphicon glyphicon glyphicon-camera" aria-hidden="true"></span>
						<?= \Yii::t('frontend', '{n, plural, =1{photo} other{# photos}}', ['n' => count($item_room->images)]) ?>
					</a>
				</div>
				<div class="col-sm-9">
					<div class="room-description">
						<?= $item_room->{'description_' . $l} ?>
					</div>
					<?= $form->field($item, 'room_id', [
						'inputOptions' => [
							'name' => 'items['.$index.'][OrderItem][room_id]'
						],
						'template' => '{input}',
					])->hiddenInput() ?>
					<?= $form->field($item, 'adults', [
						'inputOptions' => [
							'name' => 'items['.$index.'][OrderItem][adults]'
						],
						'template' => '{input}',
					])->hiddenInput() ?>
					<?= $form->field($item, 'children', [
						'inputOptions' => [
							'name' => 'items['.$index.'][OrderItem][children]'
						],
						'template' => '{input}',
					])->hiddenInput() ?>
					<?= $form->field($item, 'kids', [
						'inputOptions' => [
							'name' => 'items['.$index.'][OrderItem][kids]'
						],
						'template' => '{input}',
					])->hiddenInput() ?>
					<div class="row room-form">
						<div class="col-sm-2">
							<?= $form->field($item, 'guest_address', [
								'inputOptions' => [
									'name' => 'items['.$index.'][OrderItem][guest_address]',
								]
							])->dropDownList(\yii\helpers\ArrayHelper::merge(['' => ''], \common\components\ListAddressType::getSelectOptions()))?>
						</div>
						<div class="col-sm-5">
							<?= $form->field($item, 'guest_name', [
								'inputOptions' => [
									'name' => 'items['.$index.'][OrderItem][guest_name]'
								]
							]) ?>
						</div>
						<div class="col-sm-5">
							<?= $form->field($item, 'guest_surname', [
								'inputOptions' => [
									'name' => 'items['.$index.'][OrderItem][guest_surname]'
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
        <table class="pull-right">
            <tbody>
                <tr>
                    <td class="orderOptions">
                        <?php // частичная оплата при подключенной яндекскассе ?>
                        <?php if ($hotel->allow_partial_pay &&
                            ((trim($hotel->partner->shopPassword) != ''
                                || trim($hotel->partner->shopId) != ''
                                || trim($hotel->partner->scid) != '' )))
                            echo $form->field($orderForm, 'partial_pay', [
                                'enableClientValidation' => false])
                                ->checkbox([
                                    'class' => 'partial-pay',
                                    'data-uncheck' => '.checkin_fullpay',
                                    'data-disable' => '.checkin_fullpay',
                                ])
                                ->label(\Yii::t('frontend', 'I want to pay {p}% ({s}) now and the rest at check-in.', [
                                    'p' => $orderForm->partial_pay_percent,
                                    's' => number_format($orderForm->sum * (1 - $orderForm->partial_pay_percent / 100), 2, '.', ' ') . '&nbsp;' . $hotel->currency->code
                                ])) ?>

                        <?php // возможность оплаты при ?>
                        <?php if ($hotel->partner->allow_payment_via_bank_transfer): ?>
                            <?= $form->field($orderForm, 'payment_via_bank_transfer',[
                                'enableClientValidation' => false
                            ])
                                ->checkbox([
                                    'class' => 'payment_via_bank_transfer',
                                    'data-uncheck' => '.checkin_fullpay',
                                    'data-disable' => '.checkin_fullpay',
                                ])
                                ->label(\Yii::t('frontend', 'I want to pay via bank transfer'))?>
                        <?php endif; ?>

                        <?php // полная оплата при соответствующей отметке ?>
                        <?php if ($hotel->partner->allow_checkin_fullpay): ?>
                            <?= $form->field($orderForm, 'checkin_fullpay', [
                                'enableClientValidation' => false])
                                ->checkbox([
                                    'class' => 'checkin_fullpay',
                                    'data-uncheck' => '.payment_via_bank_transfer .partial-pay',
                                    'data-disable' => '.payment_via_bank_transfer .partial-pay',
                                ])
                                ->label(\Yii::t('frontend', 'I want to make full payment at check in')) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= \yii\helpers\Html::submitButton(\Yii::t('frontend', 'Next'), [
                            'class' => 'btn btn-primary btn-lg '
                        ]) ?></td>
                </tr>
            </tbody>
        </table>
	</div>
</div>

<?php \yii\bootstrap\ActiveForm::end(); ?>