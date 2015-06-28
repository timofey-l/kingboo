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
            [['dateFrom', 'dateTo', 'dateFromB', 'dateToB', 'minSum', 'maxSum', 'code', 'valueType', 'additive', 'applyForCheckIn'], 'safe'],
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
            'value' => Yii::t('pricerules', 'Value'),
            'valueType' => Yii::t('pricerules', 'Type of value'),
            'counter' => Yii::t('pricerules', 'Counter'),
            'additive' => Yii::t('pricerules', 'Additive'),
            'active' => Yii::t('pricerules', 'active'),
            'params' => Yii::t('pricerules', 'Params'),
            'minSum' => Yii::t('pricerules', 'Minimal sum of discount'),
            'maxSum' => Yii::t('pricerules', 'Maximal sum of discount'),
            'code' => Yii::t('pricerules', 'Discount promo code'),
            'applyForCheckIn' => Yii::t('pricerules', 'Apply range for check-in date'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'dateFrom' => Yii::t('pricerules', 'Commencement of discount'),
            'dateTo' => Yii::t('pricerules', 'Expiry date discount'),
            'dateFromB' => Yii::t('pricerules', 'Start booking date range'),
            'dateToB' => Yii::t('pricerules', 'End booking date range'),
            'type' => Yii::t('pricerules', 'Type of discount'),
            'value' => Yii::t('pricerules', 'Discount value'),
            'valueType' => Yii::t('pricerules', 'Type of value (% or fixed sum)'),
            'additive' => Yii::t('pricerules', 'Additive discount will be applyed all time. All additive discounts will be summarized and added to one maximal non-additive discount.'),
            'minSum' => Yii::t('pricerules', 'If discount of sum for single day will be less than this sum it will be increased to this value'),
            'maxSum' => Yii::t('pricerules', 'If discount of sum for single day will be more than this sum it will be decreased to this value'),
            'code' => Yii::t('pricerules', 'Check code for discount'),
            'applyForCheckIn' => Yii::t('pricerules', 'If checked check-in date should be in living range. Will affect all days in range you booking'),
        ];
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
        if (parent::beforeSave($insert)
            && !\Yii::$app->user->isGuest
            && !($this->partner_id > 0)) {
            $this->partner_id = Yii::$app->user->id;
            $this->active = 1;
            return true;
        } else {
            return false;
        }
    }
}
