<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property integer $id
 * @property string $route
 * @property string $title_ru
 * @property string $title_en
 * @property string $content_ru
 * @property string $content_en
 * @property boolean $active
 */
class Page extends \yii\db\ActiveRecord
{
    private static $current = null;
    private static $main = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @return null
     */
    public static function getMain()
    {
        return self::$main;
    }

    /**
     * @param null $main
     */
    public static function setMain($main)
    {
        self::$main = $main;
    }

    /**
     * @return null
     */
    public static function getCurrent()
    {
        return self::$current;
    }

    /**
     * @param null $current
     */
    public static function setCurrent($current)
    {
        self::$current = $current;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route'], 'required'],
            [['content_ru', 'content_en'], 'string'],
            [['active'], 'boolean'],
            [['route', 'title_ru', 'title_en'], 'string', 'max' => 255],
            [['route'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('page', 'ID'),
            'route' => Yii::t('page', 'Route'),
            'title_ru' => Yii::t('page', 'Title Ru'),
            'title_en' => Yii::t('page', 'Title En'),
            'content_ru' => Yii::t('page', 'Content Ru'),
            'content_en' => Yii::t('page', 'Content En'),
            'active' => Yii::t('page', 'Active'),
        ];
    }
}
