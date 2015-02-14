<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\Sortable;
use yii\jui\JuiAsset;
JuiAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\models\LookupField */
/* @var $form yii\widgets\ActiveForm */
/* @var $langs Array */
?>

<div class="lookup-field-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <h2><?= Yii::t('backend_models','Values') ?></h2>

    <div class="row">
        <div class="col-lg-2">
            <button type="button" class="btn btn-success" id="addValueButton" onclick="addValues()"><?= Yii::t('backend_models', 'Add value') ?></button>
        </div>
    </div>

    <ul class="container" id="valuesContainer">
        <?php if (!$model->isNewRecord): ?>

        <?php endif ?>
    </ul>

    <script>
        function delValues(el) {

            $(el).parent().parent().remove();
        }

        function addValues() {
            var row = $('<li class="row values-row" >');
            var hash = Math.round(Math.random()*Math.pow(10,15)).toString();

            var moveButton = $('<div class="dragButton"><span class="glyphicon glyphicon-sort"></span></div>');
            row.append(moveButton);

            <?php foreach ($langs as $lang): ?>
            <?php /** @var $lang common\models\Lang */ ?>
            var col = $('<div class="col-md-<?= floor(10/count($langs)) ?>"><div class="input-group">' +
            '<span class="input-group-addon" id="val_<?= $lang->url ?>_'+hash+'"><?= $lang->name ?></span>' +
            '<input name="LookupField[values][<?= $lang->url ?>][]" type="text" class="form-control" placeholder="<?= Yii::t('backend_models','Enter item value') ?>" aria-describedby="val_<?= $lang->url ?>_'+hash+'">' +
            '</div>');
            row.append(col);
            <?php endforeach ?>
            var delButton = $('<div class="col-md-1"><button type="button" onclick="delValues(this)" class="btn btn-danger"><?= Yii::t('backend_models','Delete') ?></button></div>');
            row.append(delButton);
            $('#valuesContainer').append(row);
        }

        window.addEventListener('load', function(){
            $('#valuesContainer').sortable();
            $('#valuesContainer').disableSelection();
        });
    </script>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend_models', 'Create') : Yii::t('backend_models', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <style>
        #valuesContainer {

        }
        #valuesContainer li {
            display: block;
            list-style: none;
            margin: 10px 0;
            padding: 10px 0;
            padding-top: 0;
        }

        #valuesContainer li:not(:last-child):not(.ui-sortable-helper) {
            border-bottom: 1px solid #ccc;
        }

        #valuesContainer .dragButton {
            display: block;
            float: left;
            height: 35px;
            background: #f8f8f8;
            width: 35px;
            text-align: center;
            line-height: 35px;
            border-radius: 18px;
        }

        #valuesContainer .dragButton span {
            line-height: 35px;
        }
    </style>
</div>
