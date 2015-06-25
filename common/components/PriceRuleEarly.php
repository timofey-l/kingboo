<?php
namespace common\components;

use common\models\PriceRules;
use \Yii;

/**
 * Скидка при предварительном бронировании
 * Проверка на включение сегодняшней даты в диапазон [dateFrom; dateTo]
 *
 * @package common\components
 */
class PriceRuleEarly extends PriceRules
{
    // значение скиди
    public $amount = 0;

    // тип скидки
    // 0 = проценты
    // 1 = фиксированная сумма
    public $valueType = 0;

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $params = unserialize($this->params);
        foreach ($params as $k => $v) {
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->params = serialize([
                'valueType'   => $this->valueType,
                'amount' => $this->amount,
            ]);

            return true;
        } else {
            return false;
        }
    }
}
