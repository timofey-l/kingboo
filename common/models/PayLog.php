<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pay_logs}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $timestamp
 * @property string $postParams
 * @property string $serverParams
 * @property integer $code
 * @property string $notes
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
            [['code'], 'integer'],
            [['type', 'notes'], 'string', 'max' => 255],
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
            'type' => Yii::t('orders', 'Type'),
            'timestamp' => Yii::t('orders', 'Timestamp'),
            'postParams' => Yii::t('orders', 'Post Params'),
            'serverParams' => Yii::t('orders', 'Server Params'),
            'code' => Yii::t('orders', 'Code'),
            'notes' => Yii::t('orders', 'Notes'),
        ];
    }
    
    /**
     * Добавляет новую запись в логи
     * @param string $type - Тип записи (yandex-check, yandex-aviso)
     */
    public function add($type) {
        $this->type = $type;
        $this->timestamp = time();
        $this->postParams = serialize(\Yii::$app->request->post());
        $this->serverParams = serialize($_SERVER);
        $this->save();
    }
    
    /**
    * Добавляет в логи наш ответ
    * 
    * @param integer $code - код ответа
    * @param string $message - Текстовое сообщение
    */
    public function response($code, $message) {
        $this->code = $code;
        $this->notes = $message;
        $this->save();
    }

}
