<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%billing_income}}".
 *
 * @property integer $id
 * @property double $sum
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
            [['sum', 'date', 'account_id', 'pays_id'], 'required'],
            [['sum'], 'number'],
            [['date'], 'safe'],
            [['account_id', 'pays_id'], 'integer']
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
            'date' => Yii::t('billing_income', 'Date'),
            'account_id' => Yii::t('billing_income', 'Account ID'),
            'pays_id' => Yii::t('billing_income', 'Pays ID'),
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(BillingAccount::className(), ['id' => 'account_id']);
    }

}
