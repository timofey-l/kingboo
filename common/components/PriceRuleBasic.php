<?php
namespace common\components;

use common\models\PriceRules;
use yii\helpers\ArrayHelper;

/**
 * Обычная скидка.
 * Проценты или сумма и срок действия.
 * Также ограничения по дате бронирования и по сумме скидки.
 *
 * @package common\components
 */
class PriceRuleBasic extends PriceRules
{
    /**
     * Проверка и модификация
     *
     * @param      $array
     * @param null $params
     * @return mixed
     */
    public function processPriceInfo(&$array, $params = null)
    {
        $price_rule = [
            'days_sums' => [],
            'totalPrice' => 0,
            'totalDiscount' => 0,
        ];

        $discount_present = false;

        foreach ($array as $day => $room_price) {
            $price = $room_price['price'];
            $discount = 0;

            // проверяем день на соответствие датам
            if ($this->checkDay($day, $params)) {
                $discount_present = true;

                // применяем правило
                if ($this->valueType == 0) {
                    // проценты
                    $discount = $price * $this->value / 100;
                    $price = $price - $discount;
                } elseif ($this->valueType == 1) {
                    // фиксированная сумма
                    $discount = $this->value;
                    $price = $price - $discount;
                }

                // ограничения на сумму, если заданны
                if ($this->maxSum != null && $this->maxSum < $discount) {
                    $discount = $this->maxSum;
                }
                if ($this->minSum != null && $this->minSum > $discount) {
                    $discount = $this->minSum;
                }
            }

            $price_rule['days_sums'][$day] = [
                'discount' => $discount,
                'price' => $price,
            ];
            $price_rule['totalPrice'] += $price;
            $price_rule['totalDiscount'] += $discount;
        }
        if ($discount_present) {
            if (!is_array($array['price_rules'])) {
                $array['price_rules'] = [];
            }
            if (!is_array($array['price_rules']['others'])) {
                $array['price_rules']['other'] = [];
            }
            if (!is_array($array['price_rules']['additive'])) {
                $array['price_rules']['additive'] = [];
            }
            if ($this->additive) {
                $array['price_rules']['additive'][$this->id] = $price_rule;
            } else {
                $array['price_rules']['other'][$this->id] = $price_rule;
            }
        }
    }

    /**
     * Проверка входит ли день в скидку
     * Проверка по датам и коду
     *
     * @param $day     string  День, который проверяем
     * @param $params  array   Параметры переданные из калькулятора цен
     * @return bool    Если день подходит - true, иначе false
     */
    private function checkDay($day, $params)
    {
        $dayDate = new \DateTime($day);
        $dateFrom = new \DateTime($this->dateFrom);
        $dateTo = new \DateTime($this->dateTo);

        if ($this->dateFromB !== null) {
            $dateFromB = new \DateTime($this->dateFromB);
        } else {
            $dateFromB = null;
        }

        if ($this->dateToB !== null) {
            $dateToB = new \DateTime($this->dateToB);
        } else {
            $dateToB = null;
        }

        $bookingDate = new \DateTime();

        // проверять ли по дате бронирования
        $checkBooking = !$this->dateFromB != null && !$this->dateToB != null;
        $inBookingRange = false;
        if ($checkBooking) {
            // входит ли дата бронирования в диапазон бронирования
            $inBookingRange = ($bookingDate > $dateFromB && $bookingDate < $dateToB) || $bookingDate == $dateFromB || $bookingDate == $dateToB;
        }

        // проверять ли по диапазону ограничения дат проживания
        $checkLiving = !$this->dateFrom != null && $this->dateTo != null;
        $inLivingRange = false;
        if ($checkLiving) {
            // входит ли день проживания $day в диапазон ограничения дат проживания
            $inLivingRange = ($dayDate > $dateFrom && $dayDate < $dateTo) || $dayDate == $dateFrom || $dayDate == $dateTo;
        }

        $checkCode = !is_null($this->code);
        $codePass = false;
        if ($this->code) {
            $code = ArrayHelper::getValue($params, 'code', '');
            if ($this->code === $code) {
                $codePass = true;
            }
        }

        $result = false;

        if ($checkBooking) {
            $result = $result && $inBookingRange;
        }
        if ($checkLiving) {
            $result = $result && $inLivingRange;
        }
        if ($checkCode) {
            $result = $result && $codePass;
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return \Yii::t('pricerules', 'Discount');
    }
}
