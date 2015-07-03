<?php
/* @var $this yii\web\View */
$this->title = \Yii::t('partner_index', 'Adminstrative panel');
$assetManager = \Yii::$app->assetManager;
$l = \common\models\Lang::$current->url;

//Подключаем colorbox
$colorbox = $assetManager->publish('@bower/colorbox')[1];
$this->registerJsFile($colorbox . '/jquery.colorbox-min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($colorbox . '/i18n/jquery.colorbox-' . ($l == 'en' ? 'uk' : $l) . '.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile($colorbox . '/example1/colorbox.css', [], 'colorbox');
?>
<style>
    .hotel-image {
        width: 160px;
        margin-right: 10px;
    }
</style>

<div class="site-index">
    <div class="body-content">

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('partner_index', 'Hotels') ?></h3>
            </div>
            <div class="box-body">
                <?php foreach ($hotels as $index => $hotel): ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div id="hotel-images-carousel" class="carousel slide" data-ride="carousel">
                                <!-- Wrapper for slides -->
                                <div class="carousel-inner" role="listbox">
                                    <?php foreach ($hotel->images as $i => $image): ?>
                                        <div class="item <?= $i == 0 ? "active" : '' ?>">
                                            <img src="<?= $image->getThumbUploadUrl('image', 'bigPreview') ?>" alt="">
                                            <a class="colorbox" href="<?= $image->getUploadUrl('image') ?>"
                                               rel="hotel-images"
                                               style="display:none;"></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Controls -->
                                <a class="left carousel-control" href="#hotel-images-carousel" role="button"
                                   data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#hotel-images-carousel" role="button"
                                   data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="<?= \yii\helpers\Url::to(['/hotel/view', 'id' => $hotel->id]) ?>">
                                        <?= $hotel->{'title_' . $l} ?>
                                    </a>
                                </div>
                                <div class="col-md-12">
                                    <?php foreach (range(1, 5) as $i): ?>
                                        <i class="text-yellow <?= $i <= $hotel->category ? 'fa fa-star' : 'fa fa-star-o' ?>"></i>
                                    <?php endforeach ?>
                                </div>
                                <div class="col-md-12">
                                    <?= $hotel->address ?>
                                </div>
                                <div class="col-md-12">
                                    <small><?= $hotel->{'description_' . $l} ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($index != count($hotels) - 1): ?>
                        <hr/>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <!--end hotels-->

        <div class="row">
            <div class="col-sm-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= \Yii::t('partner_index', 'Orders') ?></h3>

                        <?php if ($newOrdersCount): ?>
                            <div class="box-tools">
                            <span data-toggle="tooltip" title="" class="badge bg-light-blue"
                                  data-original-title="<?= \Yii::t('partner_index', '{n, plural, =1{# new order} other{# new orders}}', ['n' => $newOrdersCount]) ?>">+<?= $newOrdersCount ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <?php foreach ($orders as $order): ?>
                            <li class="item">
                                <div class="product-info" style="margin-left:0;">
                                    <a href="<?= \yii\helpers\Url::to(['/orders/view', 'id' => $order->id]) ?>" class="product-title"><?= \Yii::t('partner_index', 'Order #{n}', ['n' => $order->id]) ?>
                                        <span class="small text-muted"><?= (new \DateTime($order->created_at))->format(\Yii::t('partner_index', 'd/m/Y H:i:s')) ?></span>
                                        <span class="label label-info pull-right"><?= $order->sumText ?></span>
                                        <?php if (!$order->viewed): ?>
                                            <span class="pull-right">&nbsp;</span>
                                            <span class="label label-danger pull-right"><?= \Yii::t('partner_index', 'New') ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <span class="product-description">
                                        <?= \Yii::t('partner_index', 'Nights: {n}', ['n' => $order->nights]) ?>
                                        (<?= (new \DateTime($order->dateFrom))->format(\Yii::t('partner_index', 'd/m/Y')) ?>
                                        &ndash;
                                        <?= (new \DateTime($order->dateTo))->format(\Yii::t('partner_index', 'd/m/Y')) ?>
                                        )
                                        <br/>
                                        <strong><?= $order->orderItems[0]->room->hotel->name ?></strong>
                                        /
                                        <?= $order->orderItems[0]->room->{'title_' . $l} ?>
                                    </span>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="box-footer text-center">
                        <?= \yii\helpers\Html::a(\Yii::t('partner_index', 'View all orders'), ['/orders']) ?>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= \Yii::t('partner_index', 'Support messages') ?></h3>
                            <div class="box-tools">
                                <?php if ($newMessagesCount): ?>
                                <span data-toggle="tooltip" title="" class="badge bg-light-blue"
                                  data-original-title="<?= \Yii::t('partner_index', '{n, plural, =1{# new message} other{# new messages}}', ['n' => $newMessagesCount]) ?>">+<?= $newMessagesCount ?></span>
                                <?php endif; ?>
                                <?php if ($messages): ?>
                                    <?= \yii\helpers\Html::a(
                                        '<i class="fa fa-comments-o"></i> ' . \Yii::t('partner_support', 'Start new dialog'),
                                        ['/support/create'],
                                        [
                                            'class' => 'btn btn-link pull-right'
                                        ]
                                    ) ?>
                                <?php endif; ?>
                            </div>
                    </div>
                    <div class="box-body">
                        <?php foreach($messages as $index => $message): ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?= \yii\helpers\Html::a(\yii\helpers\Html::encode($message->title), ['/support/thread', 'id' => $message->id]) ?>
                                    <span class="small text-muted pull-right">
                                        <?= (new \DateTime($message->created_at))->format(\Yii::t('partner_index','d/m/Y H:i:s')) ?>
                                    </span>
                                    <?php if ($message->newMessages): ?>
                                        <span class="pull-right">&nbsp;</span>
                                        <span class="pull-right label label-warning">
                                            +<?= $message->newMessages ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-12">
                                    <span class="text-muted">
                                        <?= \yii\helpers\StringHelper::truncateWords(\yii\helpers\Html::encode($message->text), 20) ?>
                                    </span>
                                </div>
                            </div>
                            <?php if ($index != count($messages)-1): ?>
                                <hr/>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if (!$messages): ?>
                            <div class="text-center">
                            <?= \yii\helpers\Html::a(
                                '<i class="fa fa-comments-o"></i> ' . \Yii::t('partner_support', 'Start new dialog'),
                                ['/support/create'],
                                [
                                    'class' => 'btn btn-app'
                                ]
                            ) ?>
                            </div>
                        <?php endif; ?>

                    </div>
                    <?php if ($messages): ?>
                        <div class="box-footer text-center">
                            <?= \yii\helpers\Html::a(\Yii::t('partner_index', 'View all support messages'), ['/support'], []) ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


    </div>
</div>
