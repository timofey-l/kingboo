<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%log}}".
 *
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend_log', 'ID'),
            'level' => Yii::t('backend_log', 'Level'),
            'category' => Yii::t('backend_log', 'Category'),
            'log_time' => Yii::t('backend_log', 'Log Time'),
            'prefix' => Yii::t('backend_log', 'Prefix'),
            'message' => Yii::t('backend_log', 'Message'),
        ];
    }
}
