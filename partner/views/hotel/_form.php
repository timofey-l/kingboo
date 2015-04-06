<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */
/* @var $form yii\widgets\ActiveForm */

$langs = \common\models\Lang::find()->all();


?>

<div class="hotel-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= Yii::t('hotels', 'Common information') ?></h3>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'category')->dropDownList([1,2,3,4,5]) ?>

                    <?= $form->field($model, 'timezone')->dropDownList(array_combine(DateTimeZone::listIdentifiers(),DateTimeZone::listIdentifiers())) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= Yii::t('hotels', 'Title') ?></h3>
                </div>
                <div class="box-body">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <li class="<?= $i == 0 ? "active" : "" ?>">
                                    <a href="#title_<?= $lang->url ?>" data-tabid="title_<?= $lang->url ?>" data-toggle="tab" >

                                        <?= strtoupper($lang->url) ?>

                                    </a>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                        <div class="tab-content">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <div class="tab-pane <?= $i == 0 ? "active" : "" ?>" id="title_<?= $lang->url ?>">

                                    <?= $form->field($model, 'title_' . $lang->url, [
                                        'template' => '{input}{error}'
                                    ])->textInput(['rows' => 6]) ?>

                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>

                </div>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= Yii::t('hotels', 'Description') ?></h3>
                </div>
                <div class="box-body">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <li class="<?= $i == 0 ? "active" : "" ?>">
                                    <a href="#description_<?= $lang->url ?>" data-tabid="description_<?= $lang->url ?>" data-toggle="tab" >

                                        <?= strtoupper($lang->url) ?>

                                    </a>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                        <div class="tab-content">

                            <?php foreach ($langs as $i => $lang): /** @var $lang \common\models\Lang */ ?>

                                <div class="tab-pane <?= $i == 0 ? "active" : "" ?>" id="description_<?= $lang->url ?>">

                                    <?= $form->field($model, 'description_' . $lang->url, [
                                        'template' => '{input}{error}'
                                    ])->textarea(['rows' => 6]) ?>

                                </div>

                            <?php endforeach; ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
    </div>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('hotels', 'Create') : Yii::t('hotels', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
