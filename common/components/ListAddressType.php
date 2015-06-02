<?php
namespace common\components;

use common\models\Lang;

/**
 * Class ListAddressType
 *
 * Класс с типами обращений. 'gender': 0 - М, 1 - Ж
 *
 * @package common\components
 */
class ListAddressType
{
	static $options = [
		1 => [
			'title_en' => 'Mr',
			'title_ru' => 'Г-н',
			'gender'   => 0,
			'langs'    => ['en', 'ru'],
		],
		2 => [
			'title_en' => 'Mrs',
			'title_ru' => 'Г-жа',
			'gender'   => 1,
			'langs'    => ['en', 'ru'],
		],
		3 => [
			'title_en' => 'Ms',
			'gender'   => 1,
			'langs'    => ['en'],
		],
	];

	/**
	 * Возвращает список элементов для выпадающего списка
	 * Если lang = false, то используется текущий язык
	 *
	 * @param bool $lang
	 * @return array
	 */
	static function getSelectOptions($lang = false)
	{
		if (!$lang) {
			$lang = Lang::$current->url;
		}
		$result = [];
		foreach (static::$options as $id => $item) {
			if (in_array($lang, $item['langs'])) {
				$result[$id] = $item['title_' . $lang];
			}
		}

		return $result;
	}

	/**
	 * Возвращает заголовок для заданного id
	 * Если не найдено для искомого языка - используется en
	 * Во всех остальных случаях возвращается пустая строка
	 *
	 * @param      $id
	 * @param bool $lang
	 * @return string
	 */
	static function getTitle($id, $lang = false)
	{
		if (!$lang) {
			$lang = Lang::$current->url;
		}
		if (isset(static::$options[$id])) {
			if (in_array($lang, static::$options[$id]['langs'])) {
				return static::$options[$id]['title_' . $lang];
			} else {
				return static::$options[$id]['title_en'];
			}
		} else {
			return '';
		}
	}

}