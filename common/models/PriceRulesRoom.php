<?php
namespace common\models;

use yii\db\ActiveRecord;

class PriceRulesRoom extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function tableName()
    {
        return '{{%price_rules_rooms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_rule_id', 'room_id'], 'integer'],
            [['price_rule_id', 'room_id'], 'required'],
        ];
    }
}
