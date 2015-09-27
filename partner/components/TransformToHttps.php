<?php
namespace partner\components;
 
use \Yii;
use yii\base\Component;
 
class TransformToHttps extends Component {

	public static function get($url) {
		$s = curl_init(); 
		curl_setopt($s, CURLOPT_URL, $url); 
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
		$page = curl_exec($s);
		curl_close($s);
		if (!$page) return '';

		$page = preg_replace_callback("#href=\"([^\"]+?)\"#is", function($matches) {
			//print_r($matches);
			$s = self::replace($matches);
			//echo $s.'<br>';
			return "href=\"$s\"";
		}, $page);

		$page = preg_replace_callback("#src=\"([^\"]+?)\"#is", function($matches) {
			//print_r($matches);
			$s = self::replace($matches);
			//echo $s.'<br>';
			return "src=\"$s\"";
		}, $page);

		return $page;
	}

	private static function replace($matches) {
		$base = 'https://' . \Yii::$app->params['partnerDomain'];
		if (strpos($matches[1], '/') === 0) {
			$s = $base . $matches[1];
		} elseif (strpos($matches[1], 'mailto:') === 0 || $matches[1]{0} == '{') {
			$s = $matches[1];
		} else {
			$s = $base . '/' . $matches[1];
		}
		return $s;
	}
}