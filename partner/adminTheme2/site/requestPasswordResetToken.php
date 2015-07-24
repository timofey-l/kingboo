<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \partner\models\PasswordResetRequestForm */

$this->title = \Yii::t('partner_login', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-box">
    <div class="login-logo">
        <a href="/">king-boo.com</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">

        <div class="site-request-password-reset">
            <p class="login-box-msg"><?= \Yii::t('partner_login', 'Please fill out your email. A link to reset password will be sent there.') ?></p>

            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
            <?= $form->field($model, 'email') ?>
            <div class="form-group">
                <?= Html::submitButton(\Yii::t('partner_login', 'Send'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

