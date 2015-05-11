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

	// информация номерах
	public $items;

	// контактная информация
	public $email;
	public $phone;
	public $name;
	public $surname;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['roomId', 'dateFrom', 'dateTo', 'adults', 'children', 'kids', 'name', 'surname', 'email'], 'required'],
			[['roomId', 'adults', 'children', 'kids'], 'number'],
			[['dateFrom', 'dateTo'], 'string', 'max' => 10, 'min' => 10],
			[['name', 'surname'], 'string'],
			['email', 'email'],
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
		];
	}
}