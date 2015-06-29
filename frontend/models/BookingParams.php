<?php
namespace frontend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class BookingParams extends Model
{
	public static $genderItems = [
		[
			'id'    => 0,
			'label' => 'male',
		],
		[
			'id'    => 1,
			'label' => 'female',
		]
	];
	public $roomId;
	public $hotelId;
	public $adults;
	public $children;
	public $kids;
	public $dateFrom;
	public $dateTo;
	public $code;

	public static function getGenderItems()
	{
		return ArrayHelper::map(static::$genderItems, 'id', function ($array) {
			return \Yii::t('frontend_dynamic', $array['label']);
		});
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['roomId', 'hotelId', 'adults', 'children', 'adults', 'kids'], 'integer', 'integerOnly' => true],
			[['dateFrom', 'dateTo'], 'string', 'min' => 10, 'max' => 10],

			[['kids', 'children'], 'integer', 'min' => 0, 'max' => 10],
			['adults', 'integer', 'min' => 1, 'max' => 10],

			[['roomId', 'adults', 'children', 'adults', 'dateFrom', 'dateTo'], 'required'],
		];
	}


}