<?php
namespace common\components;

use common\models\Lang;

/**
 * Class YandexHelper
 *
 * Весь функционал связанный с ЯндексКассой
 *
 * @package common\components
 */
class YandexHelper
{
	const PAY_TYPE_PC = 0; // Оплата из кошелька в Яндекс.Деньгах.
	const PAY_TYPE_AC = 1; // Оплата с произвольной банковской карты.
	const PAY_TYPE_MC = 2; // Платеж со счета мобильного телефона.
	const PAY_TYPE_GP = 3; // Оплата наличными через кассы и терминалы.
	const PAY_TYPE_WM = 4; // Оплата из кошелька в системе WebMoney.
	const PAY_TYPE_SB = 5; // Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн.
	const PAY_TYPE_MP = 6; // Оплата через мобильный терминал (mPOS).
	const PAY_TYPE_AB = 7; // Оплата через Альфа-Клик.
	const PAY_TYPE_МА = 8; // Оплата через MasterPass.
	const PAY_TYPE_PB = 9; // Оплата через Промсвязьбанк.

	public static $pay_type = [
		self::PAY_TYPE_PC => [
			'title_ru' => 'Оплата из кошелька в Яндекс.Деньгах',
			'title_en' => 'Payment from Yandex.Money purse',
		],
		self::PAY_TYPE_AC => [
			'title_ru' => 'Оплата с произвольной банковской карты',
			'title_en' => 'Payment by any bank card',
		],
		self::PAY_TYPE_MC => [
			'title_ru' => 'Платеж со счета мобильного телефона',
			'title_en' => 'Payment from the mobile phone account',
		],
		self::PAY_TYPE_GP => [
			'title_ru' => 'Оплата наличными через кассы и терминалы',
			'title_en' => ' Cash payments via cash offices and terminals',
		],
		self::PAY_TYPE_WM => [
			'title_ru' => 'Оплата из кошелька в системе WebMoney',
			'title_en' => 'Payment from  WebMoney system purse',
		],
		self::PAY_TYPE_SB => [
			'title_ru' => 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн',
			'title_en' => 'Sberbank payments: payment via SMS or Sberbank Online',
		],
		self::PAY_TYPE_MP => [
			'title_ru' => 'Оплата через мобильный терминал (mPOS)',
			'title_en' => 'Payment via mobile terminal (mPOS)',
		],
		self::PAY_TYPE_AB => [
			'title_ru' => 'Оплата через Альфа-Клик',
			'title_en' => 'Payment via Alfa-Click',
		],
		self::PAY_TYPE_MA => [
			'title_ru' => 'Оплата через MasterPass',
			'title_en' => 'Payment via MasterPass',
		],
		self::PAY_TYPE_PB => [
			'title_ru' => 'Оплата через Промсвязьбанк',
			'title_en' => 'Payment via Promsvyazbank',
		],

	];

	/**
	 * Получение заголовка по id типа оплаты
	 *
	 * @param integer $id
	 * @param bool    $lang
	 * @return mixed
	 */
	static function getPayTypeTitle($id, $lang = false)
	{
		if ($lang == false) {
			$lang = Lang::$current->url;
		}

		if (isset(static::$pay_type[$id])) {
			return static::$pay_type[$id]['title_' . $lang];
		}
	}

}