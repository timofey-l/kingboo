<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<!-- Main content -->
<div class="form-box" style="width: 580px;">
        <div class="header bg-yellow"><?= $name ?></div>

        <div class="body bg-gray">

            <div class="row ">
                <div class="col-md-1">
                    <h1 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h1>
                </div>
                <div class="col-md-11">
                    <p>
                        <?= nl2br(Html::encode($message)) ?>
                    </p>

                    <p>
                        The above error occurred while the Web server was processing your request.
                        Please contact us if you think this is a server error. Thank you.
                    </p>
                </div>
            </div>
        </div>
        <div class="footer">

            <?= Html::a(Yii::t('errors', 'Return to main page'), ['/', 'lang' => \common\models\Lang::$current]) ?>

        </div>
</div>
