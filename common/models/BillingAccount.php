<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%billing_account}}".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property double $balance
 */
class BillingAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id'], 'required'],
            [['partner_id'], 'integer'],
            [['balance'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing_account', 'ID'),
            'partner_id' => Yii::t('billing_account', 'Partner ID'),
            'balance' => Yii::t('billing_account', 'Balance'),
        ];
    }

    public function getPartner()
    {
        return $this->hasOne(PartnerUser::className(), ['id' => 'partner_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(\common\models\Currency::className(), ['id' => 'currency_id']);
    }

    public function getBalanceString()
    {
        $c = $this->currency;
        if (!is_null($c)) {
            return $c->getFormatted($this->balance);
        } else {
            return $this->balance;
        }
    }

    public function getIncomes()
    {
        return $this->hasMany(BillingIncome::className(), ['account_id' => 'id']);
    }

    public function getExpenses()
    {
        return $this->hasMany(BillingExpense::className(), ['account_id' => 'id']);
    }

    /**
     * Пересчет балланса
     *
     * TODO: проверка валюты и конвертация её при подсчёте
     */
    public function updateBalance()
    {
        $totalIncome = (float) (new Query())
            ->from(BillingIncome::tableName())
            ->sum('sum');

        $totalExpenses = (float) (new Query())
            ->from(BillingExpense::tableName())
            ->sum('sum');

        $this->balance = $totalIncome - $totalExpenses;
        $this->save();
    }
}
