<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%list_price_type}}".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 * @property integer $order
 */
class ListPriceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%list_price_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en'], 'required'],
            [['order'], 'integer'],
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
            'order' => Yii::t('common_models', 'Order'),
        ];
    }
}
