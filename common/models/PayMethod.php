<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pay_methods}}".
 *
 * @property integer $id
 * @property string $title_ru
 * @property string $title_en
 * @property integer $yandex_code
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
            [['yandex_code'], 'integer'],
            [['title_ru', 'title_en'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('support', 'ID'),
            'title_ru' => Yii::t('support', 'Title Ru'),
            'title_en' => Yii::t('support', 'Title En'),
            'yandex_code' => Yii::t('support', 'Yandex Code'),
        ];
    }
}
