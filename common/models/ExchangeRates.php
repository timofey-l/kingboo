<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%exchange_rates}}".
 *
 * @property integer $id
 * @property string $date
 * @property string $rates
 */
class ExchangeRates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exchange_rates}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['rates'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'rates' => 'Rates',
        ];
    }
}
