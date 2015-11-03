<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\db\Query;

/**
 * This is the model class for table "{{%direct_login_tokens}}".
 *
 * @property integer $id
 * @property string $token
 * @property integer $user_id
 * @property string $expire_date
 */
class DirectLoginTokens extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%direct_login_tokens}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token', 'user_id', 'expire_date'], 'required'],
            [['user_id'], 'integer'],
            [['expire_date'], 'safe'],
            [['token'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'user_id' => 'User ID',
            'expire_date' => 'Expire Date',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // удаляем просроченные ключи
        try {
            $command = \Yii::$app->db->createCommand();
            $command->delete(self::tableName(), ['<', 'date', date(\DateTime::ISO8601)])->execute();
        } catch (Exception $e) {

        }
    }
}
