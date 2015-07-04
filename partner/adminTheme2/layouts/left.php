<?php
use yii\bootstrap\Nav;
use yii\helpers\Html;
/** @var \yii\web\View $this */
$l = \common\models\Lang::$current->url;
?>
<aside class="main-sidebar" style="padding-top: 0px;">

    <section class="sidebar">

	    <ul class="sidebar-menu">
		    <li class="header"><?= Yii::t('left_menu', 'Hotels list') ?></li>
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
                    <i class="fa fa-share"></i> <span><?= $hotel->name ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">

                    <?php
                        $hotelMenu = [
                            [
                                'url' => ['hotel/view'],
                                'title' => Yii::t('hotels', 'View'),
                                'icon' => 'building',
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
                                'icon' => 'institution',
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
                        echo Html::tag('li', Html::a($hotel->name, ['hotel/view', 'id' => $hotel->id]));
                    }
                }
		    ?>
	    </ul>

	    <ul class="sidebar-menu">
		    <li class="header"></li>
	    </ul>

	    <ul class="sidebar-menu">
		    <li>
			    <?= Html::tag('li', Html::a(\Yii::t('partner_orders','Orders'), ['orders/index']), [
				    'class' => $this->context->id == 'orders' ? "active" : "",
			    ]) ?>
		    </li>
	    </ul>

        <ul class="sidebar-menu">
            <li>
                <?= Html::tag('li', Html::a(\Yii::t('partner_support', 'Support'), ['support/index']), [
                    'class' => $this->context->id == 'support' ? "active" : "",
                ]) ?>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li>
                <?= Html::tag('li', Html::a(\Yii::t('partner_pricerules', 'Discounts'), ['price-rules/index']), [
                    'class' => $this->context->id == 'price-rules' ? "active" : "",
                ]) ?>
            </li>
        </ul>

    </section>

</aside>
