<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%billing_services}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property boolean $archived
 * @property boolean $default
 * @property boolean $monthly
 * @property boolean $unique
 * @property integer $currency
 * @property double $enable_cost
 * @property double $monthly_cost
 * @property double $yearly_cost
 */
class BillingService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_services}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['archived', 'default', 'monthly', 'unique'], 'boolean'],
            [['currency'], 'integer'],
            [['enable_cost', 'monthly_cost', 'yearly_cost'], 'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing_service', 'ID'),
            'name' => Yii::t('billing_service', 'Name'),
            'description' => Yii::t('billing_service', 'Description'),
            'archived' => Yii::t('billing_service', 'Archived'),
            'default' => Yii::t('billing_service', 'Default'),
            'monthly' => Yii::t('billing_service', 'Monthly'),
            'unique' => Yii::t('billing_service', 'Unique'),
            'currency' => Yii::t('billing_service', 'Currency'),
            'enable_cost' => Yii::t('billing_service', 'Enable Cost'),
            'monthly_cost' => Yii::t('billing_service', 'Monthly Cost'),
        ];
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }
}
