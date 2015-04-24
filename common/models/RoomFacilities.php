<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_facilities}}".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 * @property integer $important
 * @property integer $order
 */
class RoomFacilities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_facilities}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en'], 'required'],
            [['important', 'order'], 'integer'],
            [['name_ru', 'name_en'], 'string', 'max' => 255],
            [['name_ru'], 'unique'],
            [['name_en'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rooms', 'ID'),
            'name_ru' => Yii::t('rooms', 'Name Ru'),
            'name_en' => Yii::t('rooms', 'Name En'),
            'important' => Yii::t('rooms', 'Important'),
            'order' => Yii::t('rooms', 'Order'),
        ];
    }
    
    /**
    * Возвращает записи из таблицы в виде массива, название на текущем языке дает в свойстве name
    * В каждой возвращаемой записи есть поле checked, оно равно true если id найден как ключ в массиве $checked
    * 
    */
    public static function options($where = false, $checked = []) {
        if ($where) {
            $a = self::find()->asArray()->Where($where)->orderBy('order')->all();
        } else {
            $a = self::find()->asArray()->orderBy('order')->all();
        }
        $name = 'name_' . Lang::$current->url;
        foreach ($a as $k=>$v) {
            $a[$k]['name'] = $v[$name];
            $a[$k]['checked'] = key_exists($v['id'], $checked);
        }
        return $a;
    }
    
    /**
    * Возвращает массив объектов, закодированный для JavaScript
    * 
    */
    public static function jsObjects() {
        return json_encode(array_map(function($el) {
            return $el->attributes;
        },self::find()->all()));
    }
    
}
