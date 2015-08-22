<?php

namespace common\components;

use common\models\Room;
use common\models\RoomAvailability;
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
            ->andWhere(['>', 'price', 0])
            ->andWhere([
                'room_id'  => $room->id,
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

        // проверка на stop_sale и количество свободных номеров
        $availibleRooms = RoomAvailability::find()
            ->where(['>=', 'date', $dateFrom])
            ->andWhere(['<', 'date', $dateTo])
            ->andWhere(['>', 'count', 0])
            ->andWhere(['stop_sale' => 0, 'room_id'  => $room->id, ])
            ->orderBy('date')
            ->all()
            ;

        if (count($availibleRooms) != $days) {
            return null;
        }

        $result = ArrayHelper::map($roomPrices, 'date', function ($element) {
            return $element;
        });

        return ['days' => $result];
    }

    /**
     * Цикл по всем вариантам заселения
     * Возвращает количество вариантов и запускает для каждого варианта функцию $f с параметрами $params
     *
     * @param Room  $room   Объект комнаты
     * @param f     function()
     * @param params mixed
     *
     * @return int
     */
    public static function bookingTypes($room, $f='', $params=false) {
        $types = 0;
        for ($i = 1; $i <= $room->adults; $i++) {
            for ($j0 = 0; $j0 <= $room->children; $j0++) {
                //kids
                for ($j1 = 0; $j1 <= $room->children; $j1++) {
                    //children
                    if ($i + $j1 + $j0 > $room->total) {
                        continue;
                    }
                    if ($j1 + $j0 > $room->children) {
                        continue;
                    }
                    if (strlen($f) > 0) {
                        $f($params);
                    }
                    $types++;
                }
            }
        }
        return $types;
    }

    /**
     * Проверяет установлена ли цена на номер
     * Возвращает true, если цена установлена, и false, если нет
     *
     * @param $room   Room  Объект комнаты
     * @param $params array Параметры формирования цены
     *
     * @return boolean|null
     */
    public static function isPriceSet($room, $params)
    {
        $date = ArrayHelper::getValue($params, 'date', false);

        $prices = RoomPrices::find()
            ->where([
                'room_id' => $room->id,
                'date'    => $date,
            ])
            ->all();

        $types = 0;
        $n = 0;
        for ($i = 1; $i <= $room->adults; $i++) {
            for ($j0 = 0; $j0 <= $room->children; $j0++) {
                //kids
                for ($j1 = 0; $j1 <= $room->children; $j1++) {
                    //children
                    if ($i + $j1 + $j0 > $room->total) {
                        continue;
                    }
                    if ($j1 + $j0 > $room->children) {
                        continue;
                    }
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

    /**
     * Проверяет установлена сколько процентов цен установлено на указанный период
     * Возвращает число от 0 до 1
     *
     * @param Room  $room   Объект комнаты
     * @param array $params Параметры формирования цены
     *
     * @return float
     */
    public static function priceSetStatistic($room, $params)
    {
        //throw new \Exception('Regular price calculator dont work. Prices dont work!!!');
        $beginDate = ArrayHelper::getValue($params, 'beginDate', false);
        $endDate = ArrayHelper::getValue($params, 'endDate', false);

        $count = RoomPrices::find()
            ->where(['room_id' => $room->id])
            ->andWhere(['>=', 'date', $beginDate])
            ->andWhere(['<=', 'date', $endDate])
            ->count();
        if (!$count) {
            $count = 0;
        }

        $types = self::bookingTypes($room);

        $bd = \DateTime::createFromFormat('Y-m-d', $beginDate);
        $ed = \DateTime::createFromFormat('Y-m-d', $endDate);
        $d = $ed->diff($bd);
        $n = $d->days;
        if ($n == 0) {
            return 1;
        }
        $n = ($n + 1) * $types;

        return round($count / $n, 2);
    }

}
