<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%price_rules}}".
 *
 * @property array   $_defaultParams
 *
 * @property integer $id
 * @property string  $dateFrom
 * @property string  $dateTo
 * @property string  $dateFromB
 * @property string  $dateToB
 * @property integer $type
 * @property float   $value
 * @property integer $valueType
 * @property integer $additive
 * @property integer $active
 * @property array   $params
 * @property string  $code
 * @property integer $applyForCheckIn
 * @property integer $partner_id
 */
class PriceRules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%price_rules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['dateFrom', 'dateTo', 'dateFromB', 'dateToB', 'minSum', 'maxSum', 'code', 'value', 'valueType', 'additive', 'applyForCheckIn'], 'safe'],
            [['type', 'valueType', 'additive', 'active', 'applyForCheckIn'], 'integer'],
            [['params'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('pricerules', 'ID'),
            'dateFrom' => Yii::t('pricerules', 'Date from'),
            'dateTo' => Yii::t('pricerules', 'Date to'),
            'dateFromB' => Yii::t('pricerules', 'Booking date from'),
            'dateToB' => Yii::t('pricerules', 'Booking date to'),
            'type' => Yii::t('pricerules', 'Type'),
            'value' => Yii::t('pricerules', 'Size of the discount'),
            'valueType' => Yii::t('pricerules', 'Type of value'),
            'counter' => Yii::t('pricerules', 'Counter'),
            'additive' => Yii::t('pricerules', 'Additive'),
            'active' => Yii::t('pricerules', 'active'),
            'params' => Yii::t('pricerules', 'Params'),
            'minSum' => Yii::t('pricerules', 'Minimum discount'),
            'maxSum' => Yii::t('pricerules', 'Maximum discount'),
            'code' => Yii::t('pricerules', 'Promotional code'),
            'applyForCheckIn' => Yii::t('pricerules', 'Apply the discount for the whole booking period'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints($name = false)
    {
        $a = [
            'dateFrom' => Yii::t('pricerules', 'Commencement of discount'),
            'dateTo' => Yii::t('pricerules', 'Expiry date discount'),
            'dateFromB' => Yii::t('pricerules', 'Start booking date range'),
            'dateToB' => Yii::t('pricerules', 'End booking date range'),
            'type' => Yii::t('pricerules', 'Type of discount'),
            //'value' => Yii::t('pricerules', 'Discount in % is calculated as a percentage of the total package price. For promoaction a fixed amount is deducted from any reservation order.'),
            'valueType' => Yii::t('pricerules', 'Type of value (% or fixed sum)'),
            'additive' => Yii::t('pricerules', 'All additive discounts are summarized in one overall discount. If the option is unchecked, only the biggest non-additive discount for booking is applied.'),
            'minSum' => Yii::t('pricerules', 'If the discount calculated in Sec. 2 is smaller than the value given here, the size of the minimum discount will be increased to this value, and the total reservation sum will be recalculated automatically.'),
            'maxSum' => Yii::t('pricerules', 'If the discount calculated in Sec. 2 is bigger than the value given here, the size of the maximum discount will be decreased to this value, and the total reservation sum will be recalculated automatically.'),
            'code' => Yii::t('pricerules', 'Discount for clients who enter the specified promotional code at reservation (eg. regular clients).'),
            'applyForCheckIn' => Yii::t('pricerules', 'Tick is the discount is valid for all reservation  days if the arrival date falls under the marked period. Do not tick if the discount is valid only for reservation days falling under the specified period, regardless of the arrival date.'),
        ];

        if ($name) {
            if (isset($a[$name])) {
                return $a[$name];
            } else {
                return '';
            }
        } else {
            return $a;
        }
    }

    /**
     * Заголовок формы создания
     * @return string
     */
    public function getTitle()
    {
        return \Yii::t('pricerules', 'Price rule');
    }

    /**
     * Обработка скидки
     */
    public function processPriceInfo($array, $params = null)
    {
        return $array;
    }

    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['id' => 'room_id'])
            ->viaTable('price_rules_rooms', ['price_rule_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!\Yii::$app->user->isGuest && !($this->partner_id > 0)) {
                $this->partner_id = Yii::$app->user->id;
            }
            if ($insert) {
                $this->active = 1;
            }
            return true;
        } else {
            return false;
        }
    }
}
