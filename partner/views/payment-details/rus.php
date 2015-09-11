<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Currency */
?>
        <div class="col-sm-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= \Yii::t('partner_profile', 'Payment details') ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                    	<div class="col-sm-12">
                            <?= $form->field($pd, 'firmName') ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $form->field($pd, 'INN') ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $form->field($pd, 'KPP') ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($pd, 'address') ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($pd, 'bank') ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $form->field($pd, 'BIK') ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $form->field($pd, 'cAccount') ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $form->field($pd, 'account') ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $form->field($pd, 'NDS') ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $form->field($pd, 'director') ?>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $form->field($pd, 'bookkeeper') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>