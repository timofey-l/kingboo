<?php
/* @var $this yii\web\View */
$this->title = \Yii::t('partner_index', 'Adminstrative panel');
$l = \common\models\Lang::$current->url;
?>
<div class="site-index">
    <div class="body-content">

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?= \Yii::t('partner_index', 'Hotels') ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <?php foreach($hotels as $hotel): ?>
                        <div class="row">
                            <div class="col-md-4">
                                <a href="<?= \yii\helpers\Url::to(['/hotel/view', 'id' => $hotel->id]) ?>">
                                    <?php if ($hotel->images): ?>
                                        <img src="<?= $hotel->images[0]->preview ?>" alt=""/>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>
