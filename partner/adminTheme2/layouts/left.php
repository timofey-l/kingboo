<?php
use yii\bootstrap\Nav;
use yii\helpers\Html;

$l = \common\models\Lang::$current->url;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

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
                    <li>
                        <a href="<?= \yii\helpers\Url::toRoute(['hotel/view', 'id' => $hotel->id]) ?>">
                            <span class="fa fa-building"></span> <?= Yii::t('hotels', 'View') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::toRoute(['hotel/update', 'id' => $hotel->id]) ?>">
                            <span class="fa fa-edit"></span> <?= Yii::t('hotels', 'Edit') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::toRoute(['hotel/facilities', 'id' => $hotel->id]) ?>">
                            <span class="fa fa-check-square-o"></span> <?= Yii::t('hotels', 'Facilities') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::toRoute(['hotel/rooms', 'id' => $hotel->id]) ?>">
                            <span class="fa fa-institution"></span> <?= Yii::t('hotels', 'Rooms') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= \yii\helpers\Url::toRoute(['hotel/images', 'id' => $hotel->id]) ?>">
                            <span class="fa fa-camera"></span> <?= Yii::t('hotels', 'Images') ?>
                        </a>
                    </li>
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
			    <?= Html::tag('li', Html::a(\Yii::t('partner_widget','Widgets'), ['widgets/index']), [
				    'class' => $this->context->id == 'widgets' ? "active" : "",
			    ]) ?>
		    </li>
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
