<?php

namespace common\components;

use common\models\Room;
use common\models\RoomPrices;
use yii\helpers\ArrayHelper;

class FixedPriceCalculator
{

	/**
	 * Вычисление цены комнаты
	 *
	 * @param Room  $room   Объект комнаты
	 * @param array $params Параметры формирования цены
	 *
	 * @return array|null Массив priceInfo или null - если комната недоступна
	 */
	public static function getPriceInfo(Room $room, $params)
	{
		$result = [];

		$dateFrom = ArrayHelper::getValue($params, 'dateFrom', false);
		$dateTo = ArrayHelper::getValue($params, 'dateTo', false);

		$roomPrices = RoomPrices::find()
			->where(['>=', 'date', $dateFrom])
			->andWhere(['<', 'date', $dateTo])
			->andWhere([
				'room_id' => $room->id,
				'adults'   => 0,
				'children' => 0,
				'kids'     => 0,
			])
			->all();

		$result = ArrayHelper::map($roomPrices, 'date', function($array){ return $array; });

		return $result;
	}

	/**
	 * Проверяет установлена ли цена на номер
	 * Возвращает true, если цена установлена, и false, если нет
	 *
	 * @param Room  $room   Объект комнаты
	 * @param array $params Параметры формирования цены
	 *
	 * @return boolean|null
	 */
	public static function isPriceSet($room, $params) {
		$date = ArrayHelper::getValue($params, 'date', false);

		$price = RoomPrices::find()
			->where([
				'room_id' => $room->id,
				'date'   => $date,
			])
			->one();

		return ($price && $price['price'] > 0);
	}
}
