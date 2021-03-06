<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lang}}".
 *
 * @property integer $id
 * @property string  $url
 * @property string  $local
 * @property string  $name
 * @property integer $default
 * @property integer $date_update
 * @property integer $date_create
 *
 * @ Lang    $current
 */
class Lang extends \yii\db\ActiveRecord
{
	static $current = null;

	/**
	 * Получение текущего языка (объект)
	 *
	 * @return null|Lang
	 */
	static function getCurrent()
	{
		if (self::$current === null) {
			self::$current = self::getDefaultLang();
		}

		return self::$current;
	}

	/**
	 * Установка текущего объекта языка и локали пользовтеля
	 *
	 * @param null $url Имя языка (ru, en и т.д.)
	 */
	static function setCurrent($url = null)
	{
		$language = self::getLangByUrl($url);
		self::$current = ($language === null) ? self::getDefaultLang() : $language;
		Yii::$app->language = self::$current->local;
		setlocale(LC_ALL, self::$current->locale);
	}


	/**
	 * Получение объекта языка отмеченного "по умолчанию"
	 *
	 * @return array|null|\yii\db\ActiveRecord
	 */
	static function getDefaultLang()
	{
		return Lang::find()->where(['default' => 1])->one();
	}

	/**
	 * Получение объекта языка по его имени
	 *
	 * @param null $url
	 * @return array|null|\yii\db\ActiveRecord
	 */
	static function getLangByUrl($url = null)
	{
		if ($url === null) {
			return null;
		} else {
			$language = Lang::find()->where('url = :url', [':url' => $url])->one();
			if ($language === null) {
				return null;
			} else {
				return $language;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%lang}}';
	}

	/**
	 * Возвращает отсортированный список языков: текущий язык ставится на первое место
	 */
	public static function sortedLangList()
	{
		$langs = self::find()->all();
		$cur_id = self::$current->id;
		$cur_lang = false;
		foreach ($langs as $k => $lang) {
			if ($cur_id == $lang->id) {
				if ($k == 0) break;
				$cur_lang = array_splice($langs, $k, 1);
				continue;
			}
		}
		if ($cur_lang) {
			array_unshift($langs, $cur_lang[0]);
		}

		return $langs;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'timestamp' => [
				'class'      => 'yii\behaviors\TimestampBehavior',
				'attributes' => [
					\yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['date_create', 'date_update'],
					\yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['date_update'],
				],
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['url', 'local', 'name'], 'required'],
			[['default', 'date_update', 'date_create'], 'integer'],
			[['url', 'local', 'name'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => Yii::t('langs', 'ID'),
			'url'         => Yii::t('langs', 'Url'),
			'local'       => Yii::t('langs', 'Local'),
			'name'        => Yii::t('langs', 'Name'),
			'default'     => Yii::t('langs', 'Default'),
			'date_update' => Yii::t('langs', 'Date Update'),
			'date_create' => Yii::t('langs', 'Date Create'),
		];
	}
}
