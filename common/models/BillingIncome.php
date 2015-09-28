<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%billing_income}}".
 *
 * @property integer $id
 * @property double $sum
 * @property integer $currency_id
 * @property string $date
 * @property integer $account_id
 * @property integer $pays_id
 */
class BillingIncome extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_income}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sum', 'currency_id', 'date', 'account_id', 'pays_id'], 'required'],
            [['sum'], 'number'],
            [['date'], 'safe'],
            [['currency_id', 'account_id', 'pays_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing_income', 'ID'),
            'sum' => Yii::t('billing_income', 'Sum'),
            'currency_id' => Yii::t('billing_income', 'Currency ID'),
            'date' => Yii::t('billing_income', 'Date'),
            'account_id' => Yii::t('billing_income', 'Account ID'),
            'pays_id' => Yii::t('billing_income', 'Pays ID'),
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(BillingAccount::className(), ['id' => 'account_id']);
    }

    public function getInvoice()
    {
        return $this->hasOne(BillingInvoice::className(), ['id' => 'invoice_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    public function afterSave($insert, $chAttrs)
    {
        if ($insert) {
            \partner\models\PartnerUser::findOne(\Yii::$app->user->id)->updateBalance();

            // Сигнал для системы сообщений
            if (isset(\Yii::$app->automaticSystemMessages)) {
            	\Yii::$app->automaticSystemMessages->setDataUpdated();
            }

        }
    }

}
