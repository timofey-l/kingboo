<?php
namespace partner\components;
 
use \Yii;
use yii\base\Component;
 
class TransformToHttps extends Component {

	public static function get($url, $script = 0) {
		if (strpos($url, \Yii::$app->params['mainDomain']) === false) {
			return;
		}

		$s = curl_init(); 
		curl_setopt($s, CURLOPT_URL, $url); 
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($s, CURLOPT_FRESH_CONNECT, true);
		$page = curl_exec($s);
		curl_close($s);
		
		$response = Yii::$app->response;
		if ($script) {
			$response->format = \yii\web\Response::FORMAT_RAW;
			if (preg_match("#\.css$#is", $url)) {
				$response->headers->set('Content-type', 'text/css');
			}
			if (preg_match("#\.js$#is", $url)) {
				$response->headers->set('Content-type', 'application/javascript');
			}
			if (preg_match("#\.jpg$|\.jpeg$#is", $url)) {
				$response->headers->set('Content-type', 'image/jpeg');
			}
			if (preg_match("#\.png$#is", $url)) {
				$response->headers->set('Content-type', 'image/png');
			}
			if (preg_match("#\.gif$#is", $url)) {
				$response->headers->set('Content-type', 'image/gif');
			}
		}

		if (!$page) {
			$response->content = '';
		} elseif ($script) {
			$response->content = $page;
		} else {
			$page = preg_replace_callback("#href=\"([^\"]+?)\"#is", function($matches) {
				$s = self::replace($matches);
				return "href=\"$s\"";
			}, $page);

			$page = preg_replace_callback("#src=\"([^\"]+?)\"#is", function($matches) {
				$s = self::replace($matches);
				return "src=\"$s\"";
			}, $page);

			$response->content = $page;
		}

		$response->send();
	}

	private static function replace($matches) {
		$base = \Yii::$app->params['partnerProtocol'] . '://' . \Yii::$app->params['partnerDomain'];
		if (strpos($matches[1], '/assets/') === 0 || strpos($matches[1], '/') === 0) {
			$s = $base . '/site/transform-to-https?script=1&url=http://' . \Yii::$app->params['mainDomain'] . $matches[1];
		} elseif (strpos($matches[1], 'mailto:') === 0 || $matches[1]{0} == '{') {
			$s = $matches[1];
		} else {
			$s = $base . '/' . $matches[1];
		}
		return $s;
	}
}