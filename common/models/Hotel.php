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
            [['partner_id', 'name', 'address', 'lng', 'lat', 'title_ru', 'title_en'], 'required'],
            [['partner_id', 'category'], 'integer'],
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
            'description_ru' => Yii::t('hotels', 'Description Ru'),
            'category' => Yii::t('hotels', 'Category'),
            'timezone' => Yii::t('hotels', 'Timezone'),
            'description_en' => Yii::t('hotels', 'Description En'),
            'title_ru' => Yii::t('hotels', 'Title Ru'),
            'title_en' => Yii::t('hotels', 'Title En'),
        ];
    }
}
