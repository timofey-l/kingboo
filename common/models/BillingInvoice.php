<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%billing_invoices}}".
 *
 * @property integer $id
 * @property integer $account_id
 * @property double $sum
 * @property string $created_at
 * @property boolean $payed
 * @property integer $system
 */
class BillingInvoice extends \yii\db\ActiveRecord
{

    const PAY_SYSTEM_YANDEX = 0;
    const PAY_SYSTEM_TEST = 100;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_invoices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'currency_id'], 'required'],
            [['account_id', 'currency_id', 'system'], 'integer'],
            [['sum'], 'number'],
            [['created_at'], 'safe'],
            [['payed'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing_invoice', 'ID'),
            'account_id' => Yii::t('billing_invoice', 'Account ID'),
            'sum' => Yii::t('billing_invoice', 'Sum'),
            'currency' => Yii::t('billing_invoice', 'Currency'),
            'created_at' => Yii::t('billing_invoice', 'Created At'),
            'payed' => Yii::t('billing_invoice', 'Payed'),
            'system' => Yii::t('billing_invoice', 'System'),
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(BillingAccount::className(), ['id' => 'account_id']);
    }

}
