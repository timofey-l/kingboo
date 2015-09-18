<?php
use common\components\ListAddressType;
use common\models\Hotel;
use common\models\Lang;
use common\models\Order;
use yii\helpers\Html;
use yii\web\View;

$l = Lang::$current->url;

/** @var View $this */
/** @var Order $order */
/** @var Hotel $hotel */
$hotel = $order->hotel;
$this->params['hotel'] = $hotel;

$this->title = \Yii::t('frontend', 'Booking is completed');

?>


<div>
    <style scoped>
        .invoice {
            margin: 0 0 0 0;
        }
        .invoice_header {
            padding-bottom: 10px;
            border-bottom: 1px solid #CCCCCC;
        }
        @media print {
            .no-print {
                display: none;
            }
            footer, nav {
                display: none;
            }
            .container {
                padding: 10px;
                width: 100%;
            }

        }
    </style>
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h3><?= \Yii::t('frontend', 'You have successfully completed the booking procedure.') ?></h3>
                <h4 class="invoice_header"><?= \Yii::t('frontend', 'Order number: <code>{n}</code>', ['n' => $order->number]) ?></h4>
            </div>
            <!-- /.col -->
        </div>
        <div class="row invoice-info">
            <div class="col-xs-4 invoice-col">
                <strong><?= \Yii::t('partner_orders', 'Contact information') ?>:</strong>
                <address>

                    <?= Html::encode(ListAddressType::getTitle($order->contact_address, $order->lang)) ?> <?= Html::encode($order->contact_name . ' ' . $order->contact_surname) ?>
                    <br>

                    <strong><?= \Yii::t('partner_orders', 'Phone') ?>:</strong>
                    <br/>
                    <?= $order->contact_phone ?>
                    <br/>

                    <strong><?= \Yii::t('partner_orders', 'Email') ?>:</strong>
                    <br/>
                    <?= $order->contact_email ?>

                </address>
            </div>
            <!-- /.col -->
            <div class="col-xs-4 invoice-col">

                <strong><?= \Yii::t('partner_orders', 'Create time') ?>:</strong>
                <br/>
                <?= (new DateTime($order->created_at))->format(\Yii::t('partner_orders', 'd/m/Y H:i:s')) ?>
                <br/>

                <strong><?= \Yii::t('partner_orders', 'Dates range') ?>:</strong>
                <br/>
                <?= (new DateTime($order->dateFrom))->format(\Yii::t('partner_orders', 'd/m/Y'))
                . '&nbsp;&ndash;&nbsp;'
                . (new DateTime($order->dateTo))->format(\Yii::t('partner_orders', 'd/m/Y'))
                ?>
                <br/>

                <strong><?= \Yii::t('partner_orders', 'Nights') ?>:</strong>
                <br/>
                <?= (new DateTime($order->dateTo))->diff(new DateTime($order->dateFrom))->days ?>
                <br/>

            </div>
            <!-- /.col -->
            <div class="col-xs-4 invoice-col">

                <strong><?= \Yii::t('partner_orders', 'Order sum') ?>:</strong>
                <br/>
                <?= $order->sum ?> <?= $order->hotel->currency->code ?>
                <br/>

                <strong><?= \Yii::t('partner_orders', 'Pay sum') ?></strong>:
                <br/>
                <?= $order->pay_sum ?> <?= $order->hotel->currency->code ?>
                <br/>
                <?php if ($order->partial_pay): ?>
                    <?= \Yii::t('partner_orders', '(partial pay {percents}%)', ['percents' => $order->partial_pay_percent]) ?>
                <?php endif; ?>
                <br/>


                <strong><?= \Yii::t('partner_orders', 'Pay status') ?></strong>:
                <br/>
                <?= \common\models\Order::getOrderStatusTitle($order->status) ?>

            </div>
            <!-- /.col -->
        </div>
        <br/>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?= \Yii::t('partner_orders', 'Room and guest') ?></th>
                        <th><?= \Yii::t('partner_orders', 'Adults') ?></th>
                        <th><?= \Yii::t('partner_orders', 'Children') ?></th>
                        <th><?= \Yii::t('partner_orders', 'Kids') ?></th>
                        <th><?= \Yii::t('partner_orders', 'Sum') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($order->orderItems as $item): /** @var \common\models\OrderItem $item */ ?>
                        <tr>
                            <td>
                                <?= $item->room->{'title_' . $l} ?>
                                <br/>
                                <i>
                                    <?= ListAddressType::getTitle($item->guest_address) ?>
                                    <?= Html::encode($item->guest_name) ?>
                                    <?= Html::encode($item->guest_surname) ?>
                                </i>
                            </td>
                            <td><?= $item->adults ?></td>
                            <td><?= $item->children ?></td>
                            <td><?= $item->kids ?></td>
                            <td><?= $item->sum ?> <?= $order->hotel->currency->code ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>


        <div class="row no-print">
            <div class="col-xs-12">
                <button id="printButton" class="btn btn-default "><span class="glyphicon glyphicon-print" aria-hidden="true"></span> <?= \Yii::t('partner_orders', 'Print') ?></button>
            </div>
        </div>
    </section>
</div>
