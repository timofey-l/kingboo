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
}
