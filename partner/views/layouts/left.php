<?php
use yii\bootstrap\Nav;
?>
<aside class="left-side sidebar-offcanvas">

    <section class="sidebar">

        <?php if (!Yii::$app->user->isGuest) : ?>
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?= $directoryAsset ?>/img/avatar5.png" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p>Hello, <?= @Yii::$app->user->identity->username ?></p>
                    <a href="<?= $directoryAsset ?>/#">
                        <i class="fa fa-circle text-success"></i> Online
                    </a>
                </div>
            </div>
        <?php endif ?>

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                            <span class="input-group-btn">
                                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i
                                        class="fa fa-search"></i></button>
                            </span>
            </div>
        </form>

        <?php 
            $h = Yii::$app->user->identity->hotels;
            $items = [];
            $items[] = [
                        'label' => '<span class="fa fa-angle-down"></span><span class="text-info">' . Yii::t('left_menu', 'Hotels list') . '</span>',
                        'url' => '/hotel/index'
                        ];
            if ($h) {
                $map = array_map(function($hotel){
                    return ['label' => $hotel->{'title_' . \common\models\Lang::$current->url}, 'url' => ['hotel/view', 'id' => $hotel->id]];
                }, $h);
                $items = array_merge($items, $map);
            }
        ?>
        <?=   
        Nav::widget(
            [
                'encodeLabels' => false,
                'options' => ['class' => 'sidebar-menu'],
                'items' => $items,
            ]
        );
        ?>


    </section>

</aside>
