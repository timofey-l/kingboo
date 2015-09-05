<?php

use yii\helpers\Html;
use \common\models\Currency;
/* @var $lang string */

$assetManager = \Yii::$app->assetManager;
$adminlte = dmstr\web\AdminLteAsset::register($this);
$this->params['adminlte'] = $adminlte->baseUrl;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$directoryAsset = $adminlte->baseUrl;

if (!isset($l)) {
    $l = $order->hotel->partner->lang;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= \Yii::t('partner_orders', 'Invoice') ?></title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body onload="window.print();">
  <?php $this->beginBody() ?>
    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <?php $date = new \DateTime($order->created_at); ?>
              <?= \Yii::t('partner_orders', 'Invoice #{n} <small class="pull-right">Date: {d}</small>', ['n' => Html::encode($order->id), 'd' => Html::encode($date->format(\Yii::t('partner_orders', 'd/m/Y')))]) ?>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-12">
            <address>
              <strong><?= Html::encode($paymentDetails->firmName); ?></strong><br>
              <?= Html::encode($paymentDetails->attributeLabels()['INN']) ?> <?= Html::encode($paymentDetails->INN) ?>, 
              <?= Html::encode($paymentDetails->attributeLabels()['KPP']) ?> <?= Html::encode($paymentDetails->KPP) ?><br />
              <?= Html::encode($paymentDetails->address); ?><br />
              <?= \Yii::t('partner_payment_details', 'Bank') ?>: <?= Html::encode($paymentDetails->bank); ?>,
              <?= Html::encode($paymentDetails->attributeLabels()['BIK']) ?> <?= Html::encode($paymentDetails->BIK) ?>,
              <?= \Yii::t('partner_payment_details', 'cor. acc.') ?> <?= Html::encode($paymentDetails->cAccount) ?>,
              <?= \Yii::t('partner_payment_details', 'acc.') ?> <?= Html::encode($paymentDetails->account) ?>
            </address>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th><?= \Yii::t('partner_orders','Number') ?></th>
                  <th><?= \Yii::t('partner_orders','Dates') ?></th>
                  <th><?= \Yii::t('partner_orders','Sum') ?></th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($order->orderItems as $item) { ?>
                <tr>
                  <td><?= \Yii::t('partner_orders', 'Order #{n}', ['n' => Html::encode($order->number)]) ?><br /><?= $item->room->{'title_' . $l} ?></td>
                  <td><?= (new DateTime($order->dateFrom))->format(\Yii::t('partner_orders', 'd/m/Y')). '&nbsp;&ndash;&nbsp;'
					           . (new DateTime($order->dateTo))->format(\Yii::t('partner_orders', 'd/m/Y'))?></td>
                  <td><?= $order->hotel->currency->convertToFormatted($item->sum, 'RUB', $order->hotel->partner->currency_exchange_percent) ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-6 col-xs-offset-6">
            <div class="table-responsive">
              <table class="table">
                <tr>
                  <th style="width:50%"><?= \Yii::t('partner_orders', 'Total amount') ?>:</th>
                  <td><?= $order->hotel->currency->convertToFormatted($order->sum, 'RUB', $order->hotel->partner->currency_exchange_percent) ?></td>
                </tr>
                <tr>
                  <th style="width:50%"><?= \Yii::t('partner_orders', 'Pay now') ?>, %:</th>
                  <td><?= Html::encode($order->partial_pay_percent) ?>%</td>
                </tr>
                <tr>
                  <th style="width:50%"><?= \Yii::t('partner_orders', 'Pay now') ?>:</th>
                  <td><?= $order->hotel->currency->convertToFormatted($order->pay_sum, 'RUB', $order->hotel->partner->currency_exchange_percent) ?></td>
                </tr>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- ./wrapper -->

    <!-- AdminLTE App -->
    <script src="../../dist/js/app.min.js"></script>
    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>