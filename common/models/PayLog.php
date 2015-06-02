<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pay_logs}}".
 *
 * @property integer $id
 * @property string $timestamp
 * @property string $postParams
 * @property string $serverParams
 */
class PayLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_logs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['postParams', 'serverParams'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('orders', 'ID'),
            'timestamp' => Yii::t('orders', 'Timestamp'),
            'postParams' => Yii::t('orders', 'Post Params'),
            'serverParams' => Yii::t('orders', 'Server Params'),
        ];
    }
}
