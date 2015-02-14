<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%lookup_values}}".
 *
 * @property integer $id
 * @property integer $lookup_field_id
 * @property string $value_ru
 * @property string $value_en
 */
class LookupValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lookup_values}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lookup_field_id'], 'integer'],
            [['value_ru', 'value_en'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend_models', 'ID'),
            'lookup_field_id' => Yii::t('backend_models', 'Lookup Field ID'),
            'value_ru' => Yii::t('backend_models', 'Value Ru'),
            'value_en' => Yii::t('backend_models', 'Value En'),
        ];
    }
}
