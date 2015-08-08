<?php
namespace partner\models;

use yii\base\Model;

class ProfileForm extends Model
{
    var $phone;
    var $company_name;

    var $password;
    var $payMethods;

    var $shopId;
    var $scid;
    var $shopPassword;
    var $allow_checkin_fullpay;
    var $allow_payment_via_bank_transfer;

    public function rules() {
        return [
            [['scid', 'shopId', 'shopPassword', 'phone', 'company_name'], 'string'],
            ['password', 'string', 'max' => 32],
            ['payMethods', 'each' , 'rule' => ['integer']],
            [['allow_checkin_fullpay', 'allow_payment_via_bank_transfer'], 'integer'],
        ];
    }

    public function attributeLabels() {
        return [
            'password' => \Yii::t('partner_profile', 'Password'),
            'payMethods' => \Yii::t('partner_profile', 'Payment methods'),
            'scid' => \Yii::t('partner_profile', 'Showcase identifier (scid)'),
            'shopId' => \Yii::t('partner_profile', 'Shop identifier (shopId)'),
            'shopPassword' => \Yii::t('partner_profile', 'Shop password (shopPassword)'),
            'allow_checkin_fullpay' => \Yii::t('partner_profile', 'Enable full payment at check in option'),
            'allow_payment_via_bank_transfer' => \Yii::t('partner_profile', 'Enable payment via bank transfer'),
            'phone' => \Yii::t('partner_profile', 'Cell phone number'),
            'company_name' => \Yii::t('partner_profile', 'Name of the legal person'),
        ];
    }

}