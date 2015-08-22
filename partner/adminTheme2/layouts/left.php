<?php
use yii\bootstrap\Nav;
use yii\helpers\Html;
/** @var \yii\web\View $this */
$l = \common\models\Lang::$current->url;
?>
<aside class="main-sidebar" style="padding-top: 0px;">

    <section class="sidebar">

	    <ul class="sidebar-menu">
		    <li class="header hotels-header"><?= Yii::t('left_menu', 'Hotels list') ?></li>
		    <?php 
                //Вывод списка отелей
                if ($this->context->id == 'hotel') {
                    $hotel_id = isset($this->context->actionParams['id']) ? $this->context->actionParams['id'] : false;
                } else {
                    $hotel_id = false;
                }
                foreach(Yii::$app->user->identity->hotels as $hotel) {
                    if ($hotel->id == $hotel_id) { //Если отель активный - выводим подменю
            ?>

            <li class="treeview active">
                <a href="#">
                    <i class="fa fa-building"></i> <span><?= $hotel->name ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">

                    <?php
                        $hotelMenu = [
                            [
                                'url' => ['hotel/view'],
                                'title' => Yii::t('hotels', 'View'),
                                'icon' => 'eye',
                            ],
                            [
                                'url' => ['hotel/update'],
                                'title' => Yii::t('hotels', 'Edit'),
                                'icon' => 'edit',
                            ],
                            [
                                'url' => ['hotel/facilities'],
                                'title' => Yii::t('hotels', 'Facilities'),
                                'icon' => 'check-square-o',
                            ],
                            [
                                'url' => ['hotel/rooms'],
                                'title' => Yii::t('hotels', 'Rooms'),
                                'icon' => 'hotel',
                            ],
                            [
                                'url' => ['hotel/images'],
                                'title' => Yii::t('hotels', 'Images'),
                                'icon' => 'camera',
                            ],
                            [
                                'url' => ['hotel/widgets', 'hotel/widget-create', 'hotel/update-widget'],
                                'title' => Yii::t('partner_widget', 'Widgets'),
                                'icon' => 'th',
                            ],
                            [
                                'url' => ['hotel/iframe'],
                                'title' => Yii::t('hotels', 'Frame on site'),
                                'icon' => 'code',
                            ],
                            [
                                'url' => ['hotel/css'],
                                'title' => Yii::t('hotels', 'Custom CSS'),
                                'icon' => 'css3',
                            ],
                        ]
                    ?>

                    <?php foreach($hotelMenu as $item): ?>
                        <li class="<?= in_array($this->context->id.'/'.$this->context->action->id, $item['url']) ? "active" : "" ?>">
                            <a href="<?= \yii\helpers\Url::toRoute([$item['url'][0], 'id' => $hotel->id]) ?>">
                                <span class="fa fa-<?= $item['icon'] ?>"></span> <?= $item['title']?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            <?php 
                    } else {
                        ?>
                        <li>
                        <a href="<?= \yii\helpers\URL::toRoute(['hotel/view', 'id' => $hotel->id]) ?>">
                            <i class="fa fa-building-o"></i> <span><?= $hotel->name ?></span>
                            <i class="fa fa-angle-right pull-right"></i>
                        </a>
                        </li>
                        <?php
                    }
                }
		    ?>
	    </ul>


	    <ul class="sidebar-menu">
		    <li>
			    <?= Html::tag('li', Html::a('<i class="fa fa-shopping-cart"></i>' . \Yii::t('partner_orders','Orders'), ['orders/index']), [
				    'class' => $this->context->id == 'orders' ? "active" : "",
			    ]) ?>
		    </li>
	    </ul>

        <ul class="sidebar-menu">
            <?= Html::tag('li', Html::a('<i class="fa fa-money"></i>' . \Yii::t('partner_pays', 'Incoming payments'), ['pays/index']), [
                'class' => ($this->context->id == 'site' && $this->context->action->id == 'pays' ? "active" : ""),
            ]) ?>
        </ul>

        <ul class="sidebar-menu">
            <li>
                <?= Html::tag('li', Html::a('<i class="fa fa-question"></i>' . \Yii::t('partner_support', 'Support'), ['support/index']), [
                    'class' => $this->context->id == 'support' ? "active" : "",
                ]) ?>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li>
                <?= Html::tag('li', Html::a('<i class="fa">% </i>' . \Yii::t('partner_pricerules', 'Discounts'), ['price-rules/index']), [
                    'class' => $this->context->id == 'price-rules' ? "active" : "",
                ]) ?>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <?= Html::tag('li', Html::a('<i class="fa fa-user"></i>' . \Yii::t('partner_profile', 'Profile'), ['site/profile']), [
                'class' => ($this->context->id == 'site' && $this->context->action->id == 'profile' ? "active" : ""),
            ]) ?>
        </ul>

    </section>

</aside>
