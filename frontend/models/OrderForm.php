<?php
namespace frontend\models;

use yii\base\Model;

class OrderForm extends Model
{
	// информация о заказе со страницы бронирования
	public $dateFrom;
	public $dateTo;
	public $adults;
	public $children;
	public $kids;
    public $code;
	// информация номерах
	public $items;

	// контактная информация
	public $contact_email;
	public $contact_phone;
	public $contact_name;
	public $contact_surname;
	public $contact_address;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['roomId', 'dateFrom', 'dateTo', 'adults', 'children', 'kids', 'contact_name', 'contact_surname', 'contact_email'], 'required'],
			[['roomId', 'adults', 'children', 'kids'], 'number'],
            [['dateFrom', 'dateTo'], 'string', 'max' => 10, 'min' => 10],
			[['contact_name', 'contact_surname', 'code'], 'string'],
			['contact_email', 'email'],
            [['name', 'surname', 'email', 'phone', 'code'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => \Yii::t('frontend', 'Name'),
			'surname' => \Yii::t('frontend', 'Surname'),
			'email' => \Yii::t('frontend', 'E-mail'),
			'phone' => \Yii::t('frontend', 'Phone number'),
            'code' => \Yii::t('frontend', 'Promo code'),
        ];
	}
}