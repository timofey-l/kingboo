<?php
namespace partner\components;
 
use \Yii;
use yii\base\Component;
 
class TransformToHttps extends Component {

	public static function get($url, $script = 0) {
		$s = curl_init(); 
		curl_setopt($s, CURLOPT_URL, $url); 
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
		$page = curl_exec($s);
		curl_close($s);
		if (!$page) return '';
		if ($script) return $page;

		$page = preg_replace_callback("#href=\"([^\"]+?)\"#is", function($matches) {
			$s = self::replace($matches);
			return "href=\"$s\"";
		}, $page);

		$page = preg_replace_callback("#src=\"([^\"]+?)\"#is", function($matches) {
			$s = self::replace($matches);
			return "src=\"$s\"";
		}, $page);

		return $page;
	}

	private static function replace($matches) {
		$base = 'https://' . \Yii::$app->params['partnerDomain'];
		if (strpos($matches[1], '/assets/') === 0) {
			$s = $base . '/site/transfer-to-http?script=1&url=http://' . \Yii::$app->params['mainDomain'] . $matches[1];
		} elseif (strpos($matches[1], '/') === 0) {
			$s = $base . $matches[1];
		} elseif (strpos($matches[1], 'mailto:') === 0 || $matches[1]{0} == '{') {
			$s = $matches[1];
		} else {
			$s = $base . '/' . $matches[1];
		}
		return $s;
	}
}