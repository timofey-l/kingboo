<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%billing_account_services}}".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $service_id
 * @property string $add_date
 * @property string $end_date
 * @property integer $hotel_id
 * @property boolean $active
 */
class BillingAccountServices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_account_services}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'service_id', 'add_date', 'end_date'], 'required'],
            [['account_id', 'service_id', 'hotel_id'], 'integer'],
            [['hotel_id'], 'default', 'value' => 0],
            [['add_date', 'end_date'], 'safe'],
            [['active'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing_account_services', 'ID'),
            'account_id' => Yii::t('billing_account_services', 'Account ID'),
            'service_id' => Yii::t('billing_account_services', 'Service ID'),
            'add_date' => Yii::t('billing_account_services', 'Add Date'),
            'end_date' => Yii::t('billing_account_services', 'End Date'),
            'active' => Yii::t('billing_account_services', 'Active'),
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(BillingAccount::className(), ['id' => 'account_id']);
    }

    public function getService()
    {
        return $this->hasOne(BillingService::className(), ['id' => 'service_id']);
    }

    public function getHotel()
    {
        return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
    }
}
