<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%billing_logs}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $date
 * @property string $postParams
 * @property string $serverParams
 * @property integer $code
 * @property string $notes
 */
class BillingLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_logs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'date', 'code'], 'required'],
            [['code'], 'integer'],
            [['date'], 'safe'],
            [['postParams', 'serverParams'], 'string'],
            [['type', 'notes'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing-logs', 'ID'),
            'type' => Yii::t('billing-logs', 'Type'),
            'date' => Yii::t('billing-logs', 'Date'),
            'postParams' => Yii::t('billing-logs', 'Post Params'),
            'serverParams' => Yii::t('billing-logs', 'Server Params'),
            'code' => Yii::t('billing-logs', 'Code'),
            'notes' => Yii::t('billing-logs', 'Notes'),
        ];
    }

    /**
     * Добавляет новую запись в логи
     * @param string $type - Тип записи (yandex-check, yandex-aviso)
     */
    public function add($type) {
        $this->type = $type;
        $this->date = date('Y-m-d H:i:s');
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
