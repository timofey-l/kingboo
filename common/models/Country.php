<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%countries}}".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en'], 'required'],
            [['name_ru', 'name_en'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common_models', 'ID'),
            'name_ru' => Yii::t('common_models', 'Name Ru'),
            'name_en' => Yii::t('common_models', 'Name En'),
        ];
    }
}
