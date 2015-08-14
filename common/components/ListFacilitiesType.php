<?php
namespace common\components;

use Yii;
use \common\models\Lang;

class ListFacilitiesType
{
    const TYPE_FEATURE = 1;
    const TYPE_HOTEL = 2;
    const TYPE_SPORT = 3;
    const TYPE_PAYMENT = 4;
    const TYPE_BEACH = 5;
    
    static $_options = [
        [
            'id' => 1,
            'description_ru' => '',
            'description_en' => '',
            'name_ru' => 'Услуги',
            'name_en' => 'Facilities',
        ],
        [
            'id' => 2,
            'description_ru' => '',
            'description_en' => '',
            'name_ru' => 'Категория',
            'name_en' => 'Category',
        ],
        [
            'id' => 3,
            'description_ru' => '',
            'description_en' => '',
            'name_ru' => 'Спорт',
            'name_en' => 'Sport',
        ],
        [
            'id' => 4,
            'description_ru' => 'Указанными средствами можно расплатиться непосредственно при заселении. Выбранные способы не имеют отношения к настройкам онлайн оплаты на сайте отеля.',
            'description_en' => 'The specified options are for payment directly at check in and are not related to the online payment settings on the hotel website.',
            'name_ru' => 'В отеле принимают оплату',
            'name_en' => 'The hotel accepts the following',
        ],
        [
            'id' => 5,
            'description_ru' => '',
            'description_en' => '',
            'name_ru' => 'Пляж',
            'name_en' => 'Beach',
        ]
    ];

    public static function getOptions() {
        return self::$_options;
    }
        
    /**
    * Возвращает options для использования в селектах array(id=>name) с учетом языка
    * 
    */
    public static function options($lang = false) {
        $name = $lang ? 'name_' . $lang : 'name_' . Lang::$current->url;
        $result = [];
        foreach (self::$_options as $v) {
            $result[$v['id']] = $v[$name];
        }
        return $result;
    }
    
    /**
    * Возвращает descriptions для использования в селектах array(id=>name) с учетом языка
    * 
    */
    public static function descriptions($lang = false) {
        $description = $lang ? 'description_' . $lang : 'description_' . Lang::$current->url;
        $result = [];
        foreach (self::$_options as $v) {
            $result[$v['id']] = $v[$description];
        }
        return $result;
    }

    /**
    * Если $lang=false возвращает массив по $id
    * Если $lang пропущен, возвращает запись на текущем языке
    * Если $lang есть - на заданном языке
    * 
    * @param mixed $id
    * @param mixed $lang
    */
    public static function option($id, $lang='') {
        $options = self::$_options;
        $option = [];
        foreach ($options as $o) {
            if ($o['id'] == $id) {
                $option = $o;
                break;
            }
        }
        if (!$option) return false;
        if ($lang === false) {
            return $option;
        }
        if (strlen($lang)) {
            $name = 'name_' . $lang;
        } else {
            $name = 'name_' . Lang::$current->url;
        }
        
        return $option[$name];
    }
    
    /**
    * Возвращает массив объектов, закодированный для JavaScript
    * 
    */
    public static function jsObjects() {
        $a = [];
        $b = self::$_options;
        foreach ($b as $k=>$o) {
            $a[$k] = json_encode($o);
        }
        return '['.implode(',',$a).']';
    }
    
}
