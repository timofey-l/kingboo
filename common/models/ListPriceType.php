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
    const TYPE_FIXED = 1; //Значение id для фиксированной цены
    const TYPE_GUESTS = 2; //Значение id для цены, зависящей от количества гостей
    
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
    
    /**
    * Возвращает options для использования моели в селектах array(id=>name)
    * 
    */
    public static function getOptions() {
        $array = self::find()->asArray()->all();
        $name = 'name_' . Lang::$current->url;
        $result = [];
        foreach ($array as $v) {
            $result[$v['id']] = $v[$name];
        }
        return $result;
    }
    
    /**
    * Возвращает массив объектов, закодированный для JavaScript
    * 
    */
    public static function getJsObjects() {
        return json_encode(array_map(function($el) {
            return $el->attributes;
        },self::find()->orderBy('order')->all()));
    }
}
