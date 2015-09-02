<?php

namespace common\models;

use Yii;
use \common\models\ExchangeRates;

/**
 * This is the model class for table "{{%currencies}}".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 * @property string $code
 * @property string $symbol
 * @property string $format
 */
class Currency extends \yii\db\ActiveRecord
{

    private static $exchangeRates;

    public function __construct() {
        parent::__construct();
        if (!isset(self::$exchangeRates)) {
            self::$exchangeRates = ExchangeRates::find()->orderBy(['date' => SORT_DESC])->one();
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currencies}}';
    }

    /**
     * Возвращает options для использования модели в селектах array(id=>name)
     *
     */
    public static function getOptions($name = 'code', $emptyval = false)
    {
        $array = self::find()->asArray()->all();
        if ($name == 'name') {
            $name = 'name_' . Lang::$current->url;
        }
        if ($emptyval) {
            $result = [0 => ''];
        } else {
            $result = [];
        }
        foreach ($array as $v) {
            $result[$v['id']] = $v[$name];
        }
        return $result;
    }

    /**
     * Возвращает массив объектов, закодированный для JavaScript
     *
     */
    public static function getJsObjects()
    {
        return json_encode(array_map(function ($el) {
            return $el->attributes;
        }, self::find()->all()));
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en', 'code', 'symbol', 'format'], 'required'],
            [['name_ru', 'name_en'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 3],
            [['symbol'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common_models', 'ID'),
            'name_ru' => Yii::t('common_models', 'Name Ru'),
            'name_en' => Yii::t('common_models', 'Name En'),
            'code' => Yii::t('common_models', 'Code'),
            'symbol' => Yii::t('common_models', 'Symbol'),
            'format' => Yii::t('common_models', 'Format'),
        ];
    }

    public function getFormatted($value)
    {
        $replace = [
            '{value}' => $value,
            '{symbol}' => $this->symbol,
            '{code}' => $this->code,
        ];
        return str_replace(array_keys($replace), array_values($replace), $this->format);
    }

    public static function getByISOCode($code) {
        return static::find()
            ->andWhere(['iso_code' => (string)$code])
            ->one();
    }

    /**
     * Конвертация из текущей валюты в указанную
     * 
     * @param decimal $x - сумма, которую надо конвертировать
     * @param string|int $to - валюта, в которую надо конвертировать (code|id)
     * @param decimal $coef - коэффициент в процентах, на который надо увеличить результат (например, 3 %)
     * 
     * @return decimal
     */
    public function convertTo($x, $to, $coef = 0) {
        $rates = unserialize(self::$exchangeRates->rates);
        if (is_integer($to)) {
            $to = @self::find()->where(['id' => $to])->one()->code;
            if (!$to) {
                throw new \Exception("Cannot find currency with id=$id");
            }
        }

        if ($this->code == 'USD') {
            if ($to == 'USD') {
                return $x;
            } else {
                $v = $x * $rates[$to];
            }
        } else {
            if ($this->code == $to) {
                return $x;
            }
            if ($to == 'USD') {
                $v = $x / $rates[$this->code];
            } else {
                $v = $x * $rates[$to] / $rates[$this->code];
            }
        }

        if ($coef) {
            $v = $v * (1 + $coef / 100);
        }
        return round($v, 2);
    }

    /**
     * convertTo() + getFormatted()
     * 
     * @param decimal $x - сумма, которую надо конвертировать
     * @param string|int $to - валюта, в которую надо конвертировать (code|id)
     * @param decimal $coef - коэффициент в процентах, на который надо увеличить результат (например, 3 %)
     * 
     * @return string
     */
    public function convertToFormatted($x, $to, $coef = 0) {
        if (is_integer($to)) {
            $toObj = @self::find()->where(['id' => $to])->one();
            if (!$toObj) {
                throw new \Exception("Cannot find currency with id=$id");
            }
            $to = $toObj->code;
        } else {
            $toObj = @self::find()->where(['code' => $to])->one();
        }
        $x = $this->convertTo($x, $to, $coef);
        return $toObj->getFormatted($x);
    }

}
