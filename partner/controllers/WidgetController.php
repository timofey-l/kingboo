<?php
namespace partner\controllers;

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
		return $this->render('js_code',[
			'code' => $code,
			'params' => json_encode([
				'url' => "http://booking.local/hotel/loceanica",
				'partnerUrl' => "http://" . $_SERVER['HTTP_HOST'] . "/"
			]),
		]);
	}

	public function actionCss($code) {
		\Yii::$app->response->format = Response::FORMAT_RAW;
		\Yii::$app->response->headers->set('Content-Type', 'text/css');
		$this->layout = false;
		return $this->render('css_code',[
			'code' => $code,
		]);
	}

}