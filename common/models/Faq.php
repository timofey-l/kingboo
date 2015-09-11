<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%faq}}".
 *
 * @property integer $id
 * @property integer $order
 * @property string $title_ru
 * @property string $title_en
 * @property string $content_ru
 * @property string $content_en
 * @property boolean $active
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%faq}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order'], 'required'],
            [['order'], 'integer'],
            [['content_ru', 'content_en'], 'string'],
            [['active'], 'boolean'],
            [['title_ru', 'title_en'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('faq', 'ID'),
            'order' => Yii::t('faq', 'Order'),
            'title_ru' => Yii::t('faq', 'Title Ru'),
            'title_en' => Yii::t('faq', 'Title En'),
            'content_ru' => Yii::t('faq', 'Content Ru'),
            'content_en' => Yii::t('faq', 'Content En'),
            'active' => Yii::t('faq', 'Active'),
        ];
    }
}
