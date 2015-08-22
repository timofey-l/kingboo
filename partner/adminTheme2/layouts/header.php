<?php
use partner\widgets\SystemMessages;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

/** @var \common\models\SupportMessage[] $messages */
$messages = \common\models\SupportMessage::findNew();
$orders = \common\models\Order::findNew();
?>

<header class="main-header">

    <?= Html::a(Yii::$app->name, Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->


                <?= SystemMessages::widget([
                    'faIcon' => 'exclamation-circle',
                    'labelType' => 'danger',
                    'width' => 300,
                ]) ?>

                <?php if ($messages): ?>
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success"><?= count($messages) ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?= \Yii::t('main', 'New messages:{n}', ['n' => count($messages)]) ?></li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <?php foreach ($messages as $index => $message): ?>
                                    <?php /** @var \common\models\SupportMessage $message */ ?>
                                    <ul class="menu">
                                        <li>

                                            <a href="<?= \yii\helpers\Url::to(['support/thread', 'id' => $message->parent_id, '#' => 'id' . $message->id]) ?>">
                                                <h4 style="margin-left: 0">
                                                    <?= \yii\helpers\StringHelper::truncateWords($message->parent->title, 5) ?>

                                                </h4>

                                                <p style="margin-left: 0"><?= \yii\helpers\StringHelper::truncateWords($message->text, 5) ?></p>
                                                <small style="bottom:0"><i
                                                        class="fa fa-clock-o"></i> <?= (new \DateTime($message->created_at))->format(\Yii::t('main', 'd/m/Y H:i')) ?>
                                                </small>
                                            </a>
                                        </li>
                                    </ul>
                                <?php endforeach; ?>
                            </li>
                            <li class="footer"><?= Html::a(\Yii::t('main', 'All messages'), ['/support']) ?></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($orders): ?>
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-cart-plus"></i>
                            <span class="label label-warning"><?= count($orders) ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?= \Yii::t('main', 'New orders: {n}', ['n' => count($orders)]) ?></li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <?php foreach ($orders as $index => $order): ?>
                                        <?php /** @var \common\models\Order $order */ ?>
                                        <li>
                                            <a href="<?= \yii\helpers\Url::to(['/orders/view', 'id' => $order->id]) ?>">
                                                <span
                                                    class="text-muted small pull-right"><?= (new \DateTime($order->created_at))->format(\Yii::t('main', 'd/m/Y H:i:s')) ?></span>
                                                <span class="label label-info"><?= $order->getSumText() ?></span>
                                                <?= \Yii::t('main', 'Order #{n}', ['n' => $order->id]) ?>
                                            </a>

                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <li class="footer">
                                <?= Html::a(\Yii::t('main', 'View all orders'), ['/orders']) ?>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="user user-menu">
                    <?= Html::a(
                        \Yii::t('main', 'Sign out') . '&nbsp;&nbsp;<i class="fa fa-sign-out"></i>',
                        ['/site/logout'],
                        ['data-method' => 'post', 'class' => 'dropdown-toggle']
                    ) ?>
                </li>

                <!-- User Account: style can be found in dropdown.less -->

            </ul>
        </div>
    </nav>
</header>
