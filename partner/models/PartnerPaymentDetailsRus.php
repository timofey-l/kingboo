<?php
namespace partner\models;
 
use \Yii;
use yii\base\Model;
 
class PartnerPaymentDetailsRus extends Model {

	var $firmName;
	var $INN;
	var $KPP;
	var $address;
	var $bank;
	var $BIK;
	var $cAccount;
	var $account;
	var $director;

    public function rules() {
        return [
            [['firmName', 'INN', 'KPP', 'address', 'bank', 'BIK', 'cAccount', 'account', 'director'], 'required'],
            [['firmName', 'INN', 'KPP', 'address', 'bank', 'BIK', 'cAccount', 'account', 'director'], 'string'],
            [['firmName', 'INN', 'KPP', 'address', 'bank', 'BIK', 'cAccount', 'account', 'director'], 'trim'],
        ];
    }

    public function attributeLabels() {
        return [
			'firmName' => \Yii::t('partner_payment_details','Name of the company'),
			'INN' => \Yii::t('partner_payment_details','INN'), 
			'KPP' => \Yii::t('partner_payment_details','KPP'), 
			'address' => \Yii::t('partner_payment_details','Address'), 
			'bank' => \Yii::t('partner_payment_details','Bank'), 
			'BIK' => \Yii::t('partner_payment_details','BIK'), 
			'cAccount' => \Yii::t('partner_payment_details','Cor. account'), 
			'account' => \Yii::t('partner_payment_details','Account'), 
			'director' => \Yii::t('partner_payment_details','Director'),
		];
	}

	public function pack() {
		$a = [];
		foreach ($this->attributeLabels() as $k => $v) {
			$a[$k] = $this->$k;
		}
		return serialize($a);
	}

	public function unpack($value) {
		$a = unserialize($value);
		if (!$a) {
			return;
		}
		foreach ($a as $k => $v) {
			$this->$k = $v;
		}
	}

}