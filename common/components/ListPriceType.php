<?php
namespace common\components;

use common\models\Lang;
use Yii;

class ListPriceType
{
	const TYPE_FIXED = 1; //Значение id для фиксированной цены
	const TYPE_GUESTS = 2; //Значение id для цены, зависящей от количества гостей

	static $_options = [
		[
			'id'      => 1,
			'name_ru' => 'Фиксированная цена',
			'name_en' => 'Fixed price',
			'class'   => '\common\components\FixedPriceCalculator',
		],
		[
			'id'      => 2,
			'name_ru' => 'Цена зависит от количества гостей',
			'name_en' => 'The price depends on the number of guests',
			'class'   => '\common\components\RegularPriceCalculator',
		]
	];

	/**
	 * Возвращает options для использования в селектах array(id=>name)
	 *
	 */
	public static function getOptions()
	{
		$name = 'name_' . Lang::$current->url;
		$result = [];
		foreach (self::$_options as $v) {
			$result[$v['id']] = $v[$name];
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
	 * @return array|bool
	 */
	public static function getOption($id, $lang = '')
	{
		$options = self::getAllOptions();
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

	public static function getAllOptions()
	{
		return self::$_options;
	}

	/**
	 * Возвращает массив объектов, закодированный для JavaScript
	 *
	 */
	public static function getJsObjects()
	{
		$a = [];
		$b = self::getAllOptions();
		foreach ($b as $k => $o) {
			$a[$k] = json_encode($o);
		}

		return '[' . implode(',', $a) . ']';
	}

	/**
	 * Возвращает элемент по id
	 *
	 * @param $id
	 * @return mixed
	 */
	public static function getById($id)
	{
		foreach (self::$_options as $option) {
			if ($option['id'] == $id) {
				return $option;
			}
		}
		return false;
	}

}
