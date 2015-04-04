<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room}}".
 *
 * @property integer $id
 * @property integer $hotel_id
 * @property string $title_ru
 * @property string $title_en
 * @property string $description_ru
 * @property string $description_en
 * @property integer $adults
 * @property integer $children
 * @property integer $total
 * @property integer $active
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
            [['hotel_id', 'title_ru', 'title_en', 'description_ru', 'description_en'], 'required'],
            [['hotel_id', 'adults', 'children', 'total', 'active'], 'integer'],
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
            'id' => Yii::t('rooms', 'ID'),
            'hotel_id' => Yii::t('rooms', 'Hotel ID'),
            'title_ru' => Yii::t('rooms', 'Title Ru'),
            'title_en' => Yii::t('rooms', 'Title En'),
            'description_ru' => Yii::t('rooms', 'Description Ru'),
            'description_en' => Yii::t('rooms', 'Description En'),
            'adults' => Yii::t('rooms', 'Adults'),
            'children' => Yii::t('rooms', 'Children'),
            'total' => Yii::t('rooms', 'Total'),
            'active' => Yii::t('rooms', 'Active'),
        ];
    }
}
