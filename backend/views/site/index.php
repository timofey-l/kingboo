<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">

            <div class="col-md-4">
                <?= \yii\helpers\Html::a(Yii::t('backend_main', 'Manage Lookups'), ['/lookups/index'],['class'=>'btn btn-default']) ?>
            </div>

        </div>

    </div>
</div>
