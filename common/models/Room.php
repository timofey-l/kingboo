<?php

namespace common\models;

use common\components\ListPriceType;
use Yii;

/**
 * This is the model class for table "{{%room}}".
 *
 * @property integer $id
 * @property integer $hotel_id
 * @property string  $title_ru
 * @property string  $title_en
 * @property string  $description_ru
 * @property string  $description_en
 * @property integer $adults
 * @property integer $children
 * @property integer $total
 * @property integer $active
 * @property integer $price_type
 * @property Hotel   $hotel
 */
class Room extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%room}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['hotel_id', 'price_type'], 'required'],
			[['title_ru'], 'required', 'when' => function ($model) {
				return empty($model->title_en);
			}, 'whenClient'                   => "function (attribute, value) {
                return !$('#room-title_en').val();
            }"],
			[['title_en'], 'required', 'when' => function ($model) {
				return empty($model->title_ru);
			}, 'whenClient'                   => "function (attribute, value) {
                return !$('#room-title_ru').val();
            }"],
			[['description_ru'], 'required', 'when' => function ($model) {
				return empty($model->description_en);
			}, 'whenClient'                         => "function (attribute, value) {
                return !$('#room-description_en').val();
            }"],
			[['description_en'], 'required', 'when' => function ($model) {
				return empty($model->description_ru);
			}, 'whenClient'                         => "function (attribute, value) {
                return !$('#room-description_ru').val();
            }"],
			[['hotel_id', 'adults', 'children', 'total', 'active'], 'integer'],
			[['price_type'], 'integer', 'min' => 1, 'max' => 2],
			[['description_ru', 'description_en'], 'string'],
			[['title_ru', 'title_en'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'             => Yii::t('rooms', 'ID'),
			'hotel_id'       => Yii::t('rooms', 'Hotel ID'),
			'title_ru'       => Yii::t('rooms', 'Title Ru'),
			'title_en'       => Yii::t('rooms', 'Title En'),
			'description_ru' => Yii::t('rooms', 'Description Ru'),
			'description_en' => Yii::t('rooms', 'Description En'),
			'adults'         => Yii::t('rooms', 'Adults'),
			'children'       => Yii::t('rooms', 'Children'),
			'total'          => Yii::t('rooms', 'Total'),
			'price_type'     => Yii::t('room', 'Price type'),
			'active'         => Yii::t('rooms', 'Active'),
		];
	}

	public function getHotel()
	{
		return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
	}

	public function getPricetype()
	{
		return ListPriceType::getOption($this->price_type);
		//return $this->hasOne(\common\components\ListPriceType::className(), ['id' => 'price_type']);
	}

	public function getImages()
	{
		return $this->hasMany(RoomImage::className(), ['room_id' => 'id']);
	}
}
