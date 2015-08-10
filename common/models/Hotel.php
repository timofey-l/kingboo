<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%hotel}}".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string  $name
 * @property string  $address
 * @property string  $contact_email
 * @property string  $contact_phone
 * @property string  $lng
 * @property string  $lat
 * @property string  $description_ru
 * @property integer $category
 * @property string  $timezone
 * @property string  $description_en
 * @property string  $title_ru
 * @property string  $title_en
 * @property boolean $allow_partial_pay
 * @property string  $partial_pay_percent
 */
class Hotel extends \yii\db\ActiveRecord
{
	const MIN_PART_PAY = 10;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%hotel}}';
	}

	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			foreach ($this->rooms as $room) {
				$room->delete();
			};
			foreach ($this->images as $image) {
				$image->delete();
			};
			$facilities = $this->facilities;
			foreach ($facilities as $f) {
				$this->unlink('facilities', $f, true);
			}
		}

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['partner_id', 'name', 'address', 'currency_id'], 'required'],
			[['title_ru'], 'required', 'when' => function ($model) {
				return empty($model->title_en);
			}, 'whenClient'                   => "function (attribute, value) {
                return !$('#hotel-title_en').val();
            }"],
			[['title_en'], 'required', 'when' => function ($model) {
				return empty($model->title_ru);
			}, 'whenClient'                   => "function (attribute, value) {
                return !$('#hotel-title_ru').val();
            }"],
			[['partner_id', 'category', 'currency_id'], 'integer'],
			[['lng', 'lat'], 'number'],
			[['description_ru', 'description_en', 'domain'], 'string'],
			[['name', 'address', 'timezone', 'title_ru', 'title_en', 'contact_email', 'contact_phone'], 'string', 'max' => 255],
			[['lng', 'lat'], 'default', 'value' => 0],
			[['allow_partial_pay'], 'integer', 'max' => 1, 'min' => 0],
			['partial_pay_percent', 'integer', 'min' => self::MIN_PART_PAY, 'max' => 100],
            ['name', 'unique'],
            ['domain', 'unique'],
			['name', 'in', 'not' => true, 'range' => ['partner', 'backend']],
		];
	}

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'name',
            ],
        ];
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                  => Yii::t('hotels', 'ID'),
			'partner_id'          => Yii::t('hotels', 'Partner ID'),
			'name'                => Yii::t('hotels', 'URL'),
			'address'             => Yii::t('hotels', 'Address'),
			'lng'                 => Yii::t('hotels', 'Lng'),
			'lat'                 => Yii::t('hotels', 'Lat'),
			'currency_id'         => Yii::t('hotels', 'Currency'),
			'description_ru'      => Yii::t('hotels', 'Description Ru'),
			'category'            => Yii::t('hotels', 'Category'),
			'timezone'            => Yii::t('hotels', 'Timezone'),
			'description_en'      => Yii::t('hotels', 'Description En'),
			'title_ru'            => Yii::t('hotels', 'Title Ru'),
			'title_en'            => Yii::t('hotels', 'Title En'),
			'allow_partial_pay'   => Yii::t('hotels', 'Allow partial pay'),
			'partial_pay_percent' => Yii::t('hotels', 'Percents to pay'),
			'domain'              => Yii::t('hotels', 'Domain'),
			'contact_email'       => Yii::t('hotels', 'Contact email'),
			'contact_phone'       => Yii::t('hotels', 'Contact phone'),
		];
	}

    public function attributeHints()
    {
        return [
            'name' => Yii::t('hotels', 'Create and enter the address of your hotel in system king.boo.com. Ex. palm_beach_hotel')
        ];
    }

	public function getRooms()
	{
		return $this->hasMany('\common\models\Room', ['hotel_id' => 'id']);
	}

	public function getImages()
	{
		return $this->hasMany('\common\models\HotelImage', ['hotel_id' => 'id']);
	}

	public function getCurrency()
	{
		return $this->hasOne('\common\models\Currency', ['id' => 'currency_id']);
	}

	public function getFacilities()
	{
		return $this->hasMany('\common\models\HotelFacilities', ['id' => 'facility_id'])
			->viaTable('rel_hotel_facility', ['hotel_id' => 'id']);
	}

	/**
	 * Возвращает массив особенностей отеля [id => поле заданное через $name]
	 *
	 * @param mixed $name
	 * @param mixed $lang
	 * @return array
	 */
	public function facilityArray($name = 'name', $lang = false)
	{
		$flist = $this->facilities;
		$a = [];
		if ($name == 'name') {
			$name = $lang ? 'name_' . $lang : 'name_' . Lang::$current->url;
		}
		foreach ($flist as $f) {
			$a[$f->id] = $f[$name];
		}

		return $a;
	}

	public function getPartner()
	{
		return $this->hasOne('\partner\models\PartnerUser', ['id' => 'partner_id']);
	}

}
