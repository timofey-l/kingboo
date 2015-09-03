<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%billing_expenses}}".
 *
 * @property integer $id
 * @property double $sum
 * @property string $date
 * @property integer $account_id
 * @property integer $service_id
 * @property string $comment
 */
class BillingExpense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_expenses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sum', 'date', 'account_id', 'service_id'], 'required'],
            [['sum'], 'number'],
            [['date'], 'safe'],
            [['account_id', 'service_id'], 'integer'],
            [['comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing_expense', 'ID'),
            'sum' => Yii::t('billing_expense', 'Sum'),
            'date' => Yii::t('billing_expense', 'Date'),
            'account_id' => Yii::t('billing_expense', 'Account ID'),
            'service_id' => Yii::t('billing_expense', 'Service ID'),
            'comment' => Yii::t('billing_expense', 'Comment'),
        ];
    }

    public function afterSave($insert)
    {
        parent::afterSave($insert);

        // если добавление записи - пересчет balance в billing_account
        if ($insert) {
            $this->account->updateBalance();
        }
    }
}
