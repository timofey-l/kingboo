<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%hotel}}".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $name
 * @property string $address
 * @property string $lng
 * @property string $lat
 * @property string $description_ru
 * @property integer $category
 * @property string $timezone
 * @property string $description_en
 * @property string $title_ru
 * @property string $title_en
 */
class Hotel extends \yii\db\ActiveRecord
{
    
    public function beforeDelete() {
        if (parent::beforeDelete()) {
            foreach ($this->rooms as $room) {
                $room->delete();
            };
            foreach ($this->images as $image) {
                $image->delete();
            };
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%hotel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'name', 'address', 'currency_id'], 'required'],
            [['title_ru'], 'required', 'when' => function($model) {
                return empty($model->title_en);
            }, 'whenClient' => "function (attribute, value) {
                return !$('#hotel-title_en').val();
            }"],
            [['title_en'], 'required', 'when' => function($model) {
                return empty($model->title_ru);
            }, 'whenClient' => "function (attribute, value) {
                return !$('#hotel-title_ru').val();
            }"],
            [['partner_id', 'category', 'currency_id'], 'integer'],
            [['lng', 'lat'], 'number'],
            [['description_ru', 'description_en'], 'string'],
            [['name', 'address', 'timezone', 'title_ru', 'title_en'], 'string', 'max' => 255],
            [['lng','lat'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('hotels', 'ID'),
            'partner_id' => Yii::t('hotels', 'Partner ID'),
            'name' => Yii::t('hotels', 'Name'),
            'address' => Yii::t('hotels', 'Address'),
            'lng' => Yii::t('hotels', 'Lng'),
            'lat' => Yii::t('hotels', 'Lat'),
            'currency_id' => Yii::t('hotels', 'Currency'),
            'description_ru' => Yii::t('hotels', 'Description Ru'),
            'category' => Yii::t('hotels', 'Category'),
            'timezone' => Yii::t('hotels', 'Timezone'),
            'description_en' => Yii::t('hotels', 'Description En'),
            'title_ru' => Yii::t('hotels', 'Title Ru'),
            'title_en' => Yii::t('hotels', 'Title En'),
        ];
    }
    
    public function getRooms() {
        return $this->hasMany('\common\models\Room', ['hotel_id' => 'id'])->inverseOf('hotel');
    }

    public function getImages() {
        return $this->hasMany('\common\models\HotelImage', ['hotel_id' => 'id'])->inverseOf('hotel');
    }
    
    public function getCurrency() {
        return $this->hasOne('\common\models\Currency', ['id' => 'currency_id']);
    }
    
    public function getFacilities() {
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
    public function facilityArray($name = 'name', $lang = false) {
        $flist = $this->facilities;
        $a = [];
        if ($name == 'name') {
            $name = $lang ? 'name_'.$lang : 'name_' . Lang::$current->url;
        }
        foreach ($flist as $f) {
            $a[$f->id] = $f[$name];
        }
        return $a;
    }
    
}
