<?php
namespace partner\controllers;

use common\models\Widget;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\View;

class WidgetController extends Controller
{

	public function actionJs($code) {
		\Yii::$app->response->format = Response::FORMAT_RAW;
		\Yii::$app->response->headers->set('Content-Type', 'application/javascript');
		$this->layout = false;
		$widget = Widget::findOne(['hash_code' => $code]);
		if ($widget  !== null) {
			return $widget->compiled_js;
		} else {
			return "";
		}
	}

	public function actionCss($code) {
		\Yii::$app->response->format = Response::FORMAT_RAW;
		\Yii::$app->response->headers->set('Content-Type', 'text/css');
		$this->layout = false;
		$widget = Widget::findOne(['hash_code' => $code]);
		if ($widget !== null) {
			return $widget->compiled_css;
		} else {
			return "";
		}
	}

}