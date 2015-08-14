<?php
use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HotelImages */

$lang = \common\models\Lang::$current->url;
$hotel_title = $model->{'title_' . $lang};
$this->title = $hotel_title;

$this->params['breadcrumbs'][] = ['label' => $hotel_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('hotels', 'Facilities');

?>
<div class="hotel-facilities">
    <?= \yii\helpers\Html::a(
        '<i class="fa fa-arrow-left"></i> ' . \Yii::t('partner_hotel', 'Back to hotel view'),
        ['view', 'id' => $model->id],
        ['class' => 'btn btn-default']) ?>
    <br/>
    <br/>
    <?php $form = ActiveForm::begin(); ?>
    <div class="">
    
        <div class="box box-solid">
            <div class="box-header with-border">
                <h2 class="box-title"><?= Yii::t('hotels', 'Facilities') ?></h2>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
    
                <?php
                    foreach ($groups as $k=>$name) {
                ?>
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $k ?>">
                                <?= $name ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?= $k ?>" class="panel-collapse collapse">
                            <div class="box-body">
                                <?php if ($descriptions[$k]) { ?>
                                <div class="row">
                                    <div class="col-sm-12"><?= $descriptions[$k] ?></div>
                                </div>
                                <?php } ?>
                                <div class="row">
                                <?php
                                    foreach ($facilities[$k] as $f) {
                                ?>
                                    <div class="col-sm-4">          
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="facilities[]" type="checkbox" value="<?= $f['id'] ?>"<?php if ($f['checked']) echo ' checked'; ?> />
                                                    <?= $f['name'] ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                <?php
                                    }
                                ?>
                                </div>
               
                                
                            </div>
                        </div>
                    </div>
               <?php        
                    }
               ?>
                    
               </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    
    </div><!-- /.row -->
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('hotels', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
