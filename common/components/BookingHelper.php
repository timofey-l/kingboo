<?php
namespace common\components;

use common\models\PriceRules;
use common\models\Room;

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
     * @param array $params Параметры для расчета цены
     *
     * @return int|null Сумма
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

        // вычислем сумму без скидок
        $sum = 0;
        foreach ($priceInfo['days'] as $day => $room_price) {
            $sum += $room_price->price;
        }

        // делаем выборку
        // все активные правила для заданной комнаты
        $price_rules = PriceRules::find()
            ->joinWith('rooms')
            ->where(['room.id' => $room->id])
            ->andWhere(['price_rules.active' => 1])
            ->all();

        // Если есть активные скидки, то проходим по всем
        if (count($price_rules) > 0) {
            foreach ($price_rules as $key => $price_rule) {
                $model = ListPriceRules::getModel($price_rule->type, $price_rule->id);
                if ($model) {
                    $priceInfo = $model->processPriceInfo($priceInfo, $params);
                }
            }
            // суммируем аддитивные если есть
            $additiveSum = 0;
            $additiveDiscount = 0;
            if (isset($priceInfo['price_rules']['additive']) && count($priceInfo['price_rules']['additive'])) {
                foreach ($priceInfo['price_rules']['additive'] as $price_rule) {
                    $additiveDiscount += $price_rule['totalDiscount'];
                    $additiveSum += $price_rule['totalPrice'];
                }
            }

            // среди остальных выбираем максимальную скидку
            $otherSum = 0;
            $otherDiscount = 0;
            if (isset($priceInfo['price_rules']['other']) && count($priceInfo['price_rules']['other'])) {
                foreach ($priceInfo['price_rules']['other'] as $price_rule_id => $p_rule) {
                    if ($otherDiscount < $p_rule['totalDiscount']) {
                        $otherDiscount = $p_rule['totalDiscount'];
                        $otherSum = $p_rule['totalPrice'];
                    }
                }
            }

            $sum = $sum - $additiveDiscount - $otherDiscount;
        }

        return $sum > 0 ? $sum : null;
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
    public static function bookingTypes($room, $f=false, &$params=false) {
        // получаем тип прайса
        $priceType = ListPriceType::getById($room->price_type);
        if ($priceType === false) {
            throw new \Exception('Price type not found');
        }

        // загружаем калькулятор и запускаем проверку
        if (class_exists($priceType['class'])) {
            $result = $priceType['class']::bookingTypes($room, $f, $params);
        } else {
            throw new \Exception("Price calc class ({$priceType['class']}) not found");
        }

        if ($result === null) {
            throw new \Exception("Room is not available");
        } else {
            return $result;
        }
    }

    /**
     * Проверяет установлена ли цена на номер
     * Возвращает true, если цена установлена, и false, если нет
     *
     * roomId - id номера
     * date - дата в формате 'YYYY-MM-DD'
     */
    public static function isPriceSet($params)
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

        // загружаем калькулятор и запускаем проверку
        if (class_exists($priceType['class'])) {
            $result = $priceType['class']::isPriceSet($room, $params);
        } else {
            throw new \Exception("Price calc class ({$priceType['class']}) not found");
        }

        if ($result === null) {
            throw new \Exception("Room is not available");
        } else {
            return $result;
        }
    }

    /**
     * Возвращает объект, описывающий цену на номер в указанный день
     * 
     *
     * roomId - id номера
     * date - дата в формате 'YYYY-MM-DD'
     */
    public static function getPrice($params)
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

        // загружаем калькулятор и запускаем проверку
        if (class_exists($priceType['class'])) {
            $result = $priceType['class']::getPrice($room, $params);
        } else {
            throw new \Exception("Price calc class ({$priceType['class']}) not found");
        }

        if ($result === null) {
            throw new \Exception("Room is not available");
        } else {
            return $result;
        }
    }

    /**
     * Возвращает массив с описаниями типов заселения
     * 
     *
     * roomId - id номера
     * date - дата в формате 'YYYY-MM-DD'
     */
    public static function getPriceTitles($params)
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

        // загружаем калькулятор и запускаем проверку
        if (class_exists($priceType['class'])) {
            $result = $priceType['class']::getPriceTitles($room, $params);
        } else {
            throw new \Exception("Price calc class ({$priceType['class']}) not found");
        }

        if ($result === null) {
            throw new \Exception("Room is not available");
        } else {
            return $result;
        }
    }

    /**
     * Проверяет установлена сколько процентов цен установлено на указанный период
     * Возвращает число от 0 до 1
     *
     * roomId - id номера
     * beginDate - дата в формате 'YYYY-MM-DD'
     * endDate - дата в формате 'YYYY-MM-DD'
     */
    public static function priceSetStatistic($params)
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

        // загружаем калькулятор и запускаем проверку
        if (class_exists($priceType['class'])) {
            $result = $priceType['class']::priceSetStatistic($room, $params);
        } else {
            throw new \Exception("Price calc class ({$priceType['class']}) not found");
        }

        if ($result === null) {
            throw new \Exception("Room is not available");
        } else {
            return $result;
        }
    }

}
