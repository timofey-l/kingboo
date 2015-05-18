<?php
namespace common\components;

use common\models\Room;
use common\models\RoomPrices;
use yii\base\Exception;
use yii\base\Object;
use yii\helpers\ArrayHelper;

class BookingHelper
{

	/**
	 * Вычисление стоимости комнаты в указанный период.
	 * С учетом типа цены, скидок и пр.
	 *
	 * Параметры:
	 * roomId - id комнаты
	 * dateFrom - дата заезда в формате 'YYYY-MM-DD'
	 * dateTo - дата отъезда в формате 'YYYY-MM-DD'
	 * adults - количество взрослых
	 * children - количество детей 7-12 лет
	 * kids - количество детей 0-7 лет
	 *
	 * @param array   $params Параметры для расчета цены
	 *
	 * @return int Сумма
	 * @throws \Exception
	 */
	public static function calcRoomPrice($params)
	{
		$room = Room::findOne($params['roomId']);
		if ($room === null) {
			throw new \Exception('Room not found');
		}

		// получаем тип прайса
		$priceType = ListPriceType::getById($room->price_type);
		if ($priceType === false) {
			throw new \Exception('Price type not found');
		}

		// загружаем калькулятор и вычисляем даты
		if (class_exists($priceType['class'])) {
			$priceInfo = $priceType['class']::getPriceInfo($room, $params);
		} else {
			throw new \Exception("Price calc class ({$priceType['class']}) not found");
		}

		if ($priceInfo === null) {
			throw new \Exception("Room is not availible");
		}

		// TODO: Добавить обработку скидок и правил формирования цен

		$sum = 0;
		foreach ($priceInfo as $day => $info) {
			$sum += $info['price'];
		}

		return $sum > 0 ? $sum : false;
	}

	/**
	 * Проверяет установлена ли цена на номер
	 * Возвращает true, если цена установлена, и false, если нет
	 * 
	 * roomId - id номера
	 * date - дата в формате 'YYYY-MM-DD'
	 */
	public static function isPriceSet($params) {
		$room = Room::findOne($params['roomId']);
		if ($room === null) {
			throw new \Exception('Room not found');
		}

		// получаем тип прайса
		$priceType = ListPriceType::getById($room->price_type);
		if ($priceType === false) {
			throw new \Exception('Price type not found');
		}

		// загружаем калькулятор и запускаем проверку
		if (class_exists($priceType['class'])) {
			$result = $priceType['class']::isPriceSet($room, $params);
		} else {
			throw new \Exception("Price calc class ({$priceType['class']}) not found");
		}

		if ($result === null) {
			throw new \Exception("Room is not availible");
		} else {
			return $result;
		}
	}

}