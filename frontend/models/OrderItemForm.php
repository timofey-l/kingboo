<?php
namespace frontend\models;

use yii\base\Model;

class OrderItemForm extends Model
{
	public $room;
	public $name;
	public $surname;
	public $roomId;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'surname', 'roomId'], 'required'],
			[['name', 'surname',], 'string'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => \Yii::t('frontend', 'Guest name'),
			'surname' => \Yii::t('frontend', 'Guest surname'),
		];
	}

}