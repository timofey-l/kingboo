<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \partner\models\SignupForm */

$this->title = \Yii::t('partner_login', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
    <div class="login-logo">
        <a href="/">king-boo.com</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <div class="body ">
                <p><?= \Yii::t('partner_login', 'Please fill out the following fields to sign up:') ?></p>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
            </div>
            <div class="footer">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
<!--        <div class="margin text-center">-->
<!--            <span>Signup using social networks</span>-->
<!--            <br/>-->
<!--            <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>-->
<!--            <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>-->
<!--            <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>-->
<!--        </div>-->
    </div>
</div>