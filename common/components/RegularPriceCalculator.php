<?php

namespace common\components;

use common\models\RoomPrices;
use yii\base\Object;
use yii\helpers\ArrayHelper;

class RegularPriceCalculator extends Object
{

	/**
	 * Вычисление цены комнаты
	 *
	 * @param $room
	 * @param $params
	 * @return array
	 * @throws \Exception
	 */
	public static function getPriceInfo($room, $params)
	{
		$result = [];

		$dateFrom = ArrayHelper::getValue($params, 'dateFrom', false);
		$dateTo = ArrayHelper::getValue($params, 'dateTo', false);
		$adults = ArrayHelper::getValue($params, 'adults', false);
		$children = ArrayHelper::getValue($params, 'children', false);
		$kids = ArrayHelper::getValue($params, 'kids', false);

		if ($dateFrom === false || $dateTo === false) {
			throw new \Exception('Dates range should be set');
		}

		$roomPrices = RoomPrices::find()
			->where(['>=', 'date', $dateFrom])
			->andWhere(['<', 'date', $dateTo])
			->andWhere([
				'room_id' => $room->id,
				'adults'   => $adults,
				'children' => $children,
				'kids'     => $kids,
			])
			->all();

		$days = (new \DateTime($dateFrom))->diff(new \DateTime($dateTo))->days;
		$prices_count = count($roomPrices);

		if ($days !== $prices_count) {
			return null;
		}

		$result = ArrayHelper::map($roomPrices, 'date', function($element){ return $element; });

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

		$prices = RoomPrices::find()
			->where([
				'room_id' => $room->id,
				'date'   => $date,
			])
			->all();

    	$types = 0;
    	$n = 0;
    	for ($i = 1; $i <= $room->adults; $i++) {
        	for ($j0 = 0; $j0 <= $room->children; $j0++) {//kids
            	for ($j1 = 0; $j1 <= $room->children; $j1++) {//children
                	if ($i + $j1 + $j0 > $room->total) continue;
                	if ($j1 + $j0 > $room->children) continue;
                	$types++;
                	foreach ($prices as $price) {
                		if ($price['adults'] == $i && $price['children'] == $j1 && $price['kids'] == $j0) {
                			$n++;
                			break;
                		}
                	}
            	}
        	}
    	}

		return ($types && $types == $n);
	}

}
