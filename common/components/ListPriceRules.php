<?php
namespace common\components;

use common\models\Lang;
use common\models\PriceRules;

/**
 * Список всех скидок, доступных на сайте
 *
 * @package common\components
 */
class ListPriceRules
{
    public static $_list = [
        [
            'id' => 0,
            'name' => 'basic',
            'class' => 'common\components\PriceRuleBasic',
            'title_ru' => 'Скидка',
            'title_en' => 'Discount',
        ],
    ];

    /**
     * Получает заголовок в нужном языке по id
     * Если язык не задан - используется определение через \common\models\Lang
     * Если заголовок с узазанным языком отсутствует - возвращается английски
     *
     * @param      $id
     * @param bool $lang
     * @return mixed
     */
    public static function getTitleById($id, $lang = false)
    {
        if ($lang === false) {
            $lang = Lang::$current->url;
        }

        $value = static::getById($id);

        if (isset($value['title_' . $lang])) {
            return $value['title_' . $lang];
        } else {
            return $value['title_en'];
        }
    }

    /**
     * Получает массив по значению id
     *
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        foreach (static::$_list as $k => $v) {
            if ($v['id'] == $id) {
                return $v;
            }
        }
        return false;
    }

    /**
     * Получает заголовок в нужном языке по name
     * Если язык не задан - используется определение через \common\models\Lang
     * Если заголовок с узазанным языком отсутствует - возвращается английски
     *
     * @param      $name
     * @param bool $lang
     * @return mixed
     */
    public static function getTitleByName($name, $lang = false)
    {
        if ($lang === false) {
            $lang = Lang::$current->url;
        }

        $value = static::getByName($name);

        if (isset($value['title_' . $lang])) {
            return $value['title_' . $lang];
        } else {
            return $value['title_en'];
        }
    }

    /**
     * Получает массив по name
     *
     * @param $name
     * @return mixed
     */
    public static function getByName($name)
    {
        foreach (static::$_list as $k => $v) {
            if ($v['name'] === $name) {
                return $v;
            }
        }
        return false;
    }

    /**
     * Возвращает нужный класс со скидкой
     *
     * @param $typeId
     * @return bool
     */
    public static function getModel($typeId, $id = false)
    {
        $a = static::getById($typeId);
        if ($a === false) {
            return false;
        }
        if ($id) {
            $model = $a['class']::findOne($id);
        } else {
            $model = new $a['class'];
            $model->type = $typeId;
        }
        if ($model === null) {
            return false;
        }
        return $model;
    }
}
