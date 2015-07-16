<?php
namespace partner\models;

use yii\base\Model;

class ProfileForm extends Model
{

    var $password;
    var $payMethods;

    var $shopId;
    var $scid;
    var $shopPassword;

    public function rules() {
        return [
            [['scid', 'shopId', 'shopPassword'], 'string'],
            ['password', 'string', 'max' => 32],
            ['payMethods', 'each' , 'rule' => ['integer']],
        ];
    }

    public function attributeLabels() {
        return [
            'password' => \Yii::t('partner_profile', 'Password'),
            'payMethods' => \Yii::t('partner_profile', 'Payment methods'),
            'scid' => \Yii::t('partner_profile', 'Showcase identifier (scid)'),
            'shopId' => \Yii::t('partner_profile', 'Shop identifier (shopId)'),
            'shopPassword' => \Yii::t('partner_profile', 'Shop password (shopPassword)'),
        ];
    }

}