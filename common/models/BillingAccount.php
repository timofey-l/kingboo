<?php

namespace common\models;

use partner\models\PartnerUser;
use Yii;
use yii\db\Query;
use common\models\Currency;

/**
 * This is the model class for table "{{%billing_account}}".
 *
 * @property integer $id
 * @property integer $partner_id - сейчас стоит уникальный индекс (потом аккаунтов может быть несколько, тогда его убрать) В PartnerUser идет учет нескольких аккаунтов
 * @property integer $currency_id
 * @property double $balance
 * @property Currency $currency
 * @property PartnerUser $partner
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
            [['partner_id', 'currency_id'], 'required'],
            [['partner_id'], 'unique'],
            [['partner_id', 'currency_id'], 'integer'],
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
            'currency_id' => Yii::t('billing_account', 'Currency ID'),
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

    public function getBalanceString($type='')
    {
        $c = $this->currency;
        if (!is_null($c)) {
            return $c->getFormatted($this->balance, $type);
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
            ->where(['account_id' => $this->id])
            ->sum('sum');

        $totalExpenses = (float) (new Query())
            ->from(BillingExpense::tableName())
            ->where(['account_id' => $this->id])
            ->sum('sum');

        $this->balance = Currency::numberFormat($totalIncome - $totalExpenses);
        $this->save(true, ['balance']);
    }

    public function getServices()
    {
        return $this->hasMany(BillingAccountServices::className(), ['account_id' => 'id']);
    }
}
