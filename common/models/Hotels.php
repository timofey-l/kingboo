<?php

namespace common\models;

use backend\models\LookupValue;
use Yii;

/**
 * This is the model class for table "{{%hotel}}".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $name
 * @property string $address
 * @property double $lng
 * @property double $lat
 * @property string $description
 * @property integer $category
 * @property string $timezone
 */
class Hotels extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%hotel}}';
    }

    public static function getPartner() {
        return static::hasOne('\partner\models\PartnerUser',['id' => 'partner_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'name', 'address', 'lng', 'lat'], 'required'],
            [['partner_id', 'category'], 'integer'],
            [['lng', 'lat'], 'number'],
            [['description'], 'string'],
            [['name', 'address', 'timezone'], 'string', 'max' => 255]
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
            'description' => Yii::t('hotels', 'Description'),
            'category' => Yii::t('hotels', 'Category'),
            'timezone' => Yii::t('hotels', 'Timezone'),
        ];
    }
}
