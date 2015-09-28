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
            [['type', 'date'], 'required'],
            [['date'], 'safe'],
            [['postParams', 'serverParams'], 'string'],
            [['type'], 'string', 'max' => 255]
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
}
