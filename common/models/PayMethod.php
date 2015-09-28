<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pay_methods}}".
 *
 * @property integer $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $yandex_code
 * @property integer $order
 */
class PayMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_methods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_ru', 'title_en', 'yandex_code'], 'required'],
            [['yandex_code'], 'string', 'min' => 2, 'max' => 2],
            [['order'], 'integer'],
            [['title_ru', 'title_en'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('pay-method', 'ID'),
            'title_ru' => Yii::t('pay-method', 'Title Ru'),
            'title_en' => Yii::t('pay-method', 'Title En'),
            'yandex_code' => Yii::t('pay-method', 'Yandex Code'),
            'order' => Yii::t('pay-method', 'Order'),
        ];
    }
}
