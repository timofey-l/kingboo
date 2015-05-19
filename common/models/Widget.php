<?php

namespace common\models;

use JShrink\Minifier;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;

/**
 * This is the model class for table "{{%widget}}".
 *
 * @property integer $id
 * @property integer $hotel_id
 * @property string $hash_code
 * @property string $params
 * @property string $comment
 * @property string $compiled_js
 * @property string $compiled_css
 */
class Widget extends \yii\db\ActiveRecord
{

	public static $defaultParams = [
		'borderColor' => [
			'type' => 'color',
			'value' => '#aaaaaa',
			'title_ru' => 'Цвет рамки',
			'title_en' => 'Border color',
		],
		'borderWidth' => [
			'type' => 'integer',
			'value' => '2',
			'title_ru' => 'Ширина рамки',
			'title_en' => 'Border width',
		],
		'showTitle' => [
			'type' => 'boolean',
			'value' => 1,
			'title_ru' => 'Показывать заголовок',
			'title_en' => 'Show title',
		],
	];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hotel_id', 'comment'], 'required'],
            [['hotel_id'], 'integer'],
            [['params', 'compiled_js', 'compiled_css'], 'string'],
            [['hash_code', 'comment'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('partner_widget', 'ID'),
            'hotel_id' => Yii::t('partner_widget', 'Hotel ID'),
            'hash_code' => Yii::t('partner_widget', 'Hash Code'),
            'params' => Yii::t('partner_widget', 'Params'),
            'comment' => Yii::t('partner_widget', 'Comment'),
            'compiled_js' => Yii::t('partner_widget', 'Compiled Js'),
            'compiled_css' => Yii::t('partner_widget', 'Compiled Css'),
        ];
    }

	/**
	 * Компиляция javascript и css для виджета
	 *
	 * @return bool
	 * @throws \Exception
	 * @internal param $id
	 */
	public function compile() {
		$view = new View();
		$params = Json::decode($this->params);

		$widget_params = [
			'partnerUrl' => 'http://'.$_SERVER['HTTP_HOST'].'/',
		];
		foreach(Json::decode($this->params) as $k => $v) {
			$widget_params[$k] = $v['value'];
		}

		$this->compiled_js = Minifier::minify($view->renderFile('@partner/views/widget/js_code.php', [
			'params' => $params,
			'code' => $this->hash_code,
			'widget' => $this,
			'widget_params' => json_encode($widget_params),//Json::encode($widget_params),
		]));

		$buffer = $view->renderFile('@partner/views/widget/css_code.php', [
			'params' => $params,
			'code' => $this->hash_code,
			'widget' => $this,
		]);
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		$buffer = str_replace(': ', ':', $buffer);
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		$this->compiled_css = $buffer;


		return true;
//		return $widget->save();
	}

	public function beforeSave($insert) {
		parent::beforeSave($insert);
		$this->compile($this->id);
		return true;
	}

}
