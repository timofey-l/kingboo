<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pays}}".
 *
 * @property integer $id
 * @property integer $checked
 * @property integer $payed
 * @property integer $order_number
 * @property integer $invoiceId
 * @property integer $customerNumber
 * @property string $orderCreatedDatetime
 * @property string $paymentDatetime
 * @property double $orderSumAmount
 * @property integer $orderSumCurrencyPaycash
 * @property integer $orderSumBankPaycash
 * @property double $shopSumAmount
 * @property integer $shopSumCurrencyPaycash
 * @property integer $shopSumBankPaycash
 * @property string $paymentPayerCode
 * @property integer $paymentType
 * @property string $postParams
 * @property string $shopId
 */
class Pay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pays}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['checked', 'payed', 'invoiceId', 'orderSumCurrencyPaycash', 'orderSumBankPaycash', 'shopSumCurrencyPaycash', 'shopSumBankPaycash', 'paymentType'], 'integer'],
            [['order_number', 'invoiceId', 'orderCreatedDatetime', 'orderSumAmount', 'orderSumCurrencyPaycash', 'orderSumBankPaycash', 'shopSumAmount', 'shopSumCurrencyPaycash', 'shopSumBankPaycash', 'paymentPayerCode', 'paymentType'], 'required'],
            [['orderCreatedDatetime', 'paymentDatetime', 'shopId'], 'safe'],
            [['orderSumAmount', 'shopSumAmount'], 'number'],
            [['postParams', 'shopId', 'customerNumber', 'order_number'], 'string'],
            [['paymentPayerCode'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('partner_pays', 'ID'),
            'checked' => Yii::t('partner_pays', 'Checked'),
            'payed' => Yii::t('partner_pays', 'Payed'),
            'order_number' => Yii::t('partner_pays', 'Order Number'),
            'invoiceId' => Yii::t('partner_pays', 'Invoice ID'),
            'customerNumber' => Yii::t('partner_pays', 'Customer Number'),
            'orderCreatedDatetime' => Yii::t('partner_pays', 'Order Created Datetime'),
            'paymentDatetime' => Yii::t('partner_pays', 'Payment Datetime'),
            'orderSumAmount' => Yii::t('partner_pays', 'Order Sum Amount'),
            'orderSumCurrencyPaycash' => Yii::t('partner_pays', 'Order Sum Currency Paycash'),
            'orderSumBankPaycash' => Yii::t('partner_pays', 'Order Sum Bank Paycash'),
            'shopSumAmount' => Yii::t('partner_pays', 'Shop Sum Amount'),
            'shopSumCurrencyPaycash' => Yii::t('partner_pays', 'Shop Sum Currency Paycash'),
            'shopSumBankPaycash' => Yii::t('partner_pays', 'Shop Sum Bank Paycash'),
            'paymentPayerCode' => Yii::t('partner_pays', 'Payment Payer Code'),
            'paymentType' => Yii::t('partner_pays', 'Payment Type'),
            'postParams' => Yii::t('partner_pays', 'Post Params'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder() {
        return $this->hasOne(Order::className(), ['number' => 'order_number']);
    }
}
