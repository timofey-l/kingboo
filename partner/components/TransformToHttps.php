<?php
namespace partner\components;
 
use \Yii;
use yii\base\Component;
 
class TransformToHttps extends Component {

	public static function get($url, $script = 0) {
		$s = curl_init(); 
		curl_setopt($s, CURLOPT_URL, $url); 
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($s, CURLOPT_HEADER, true);
		$result = curl_exec($s);

		$header_size = curl_getinfo($s, CURLINFO_HEADER_SIZE);
		curl_close($s);
		
		$response = Yii::$app->response;

		$headers = substr($result, 0, $header_size);
		$page = substr($result, $header_size);
		$headers = explode("\r\n", $headers);
		foreach ($headers as $header) {
			//echo $header.'<br>';
			if (strpos($header, 'Content-Type:') === 0) {
				$response->headers->set('Content-Type', $header);
			}
		}

		if (!$page) {
			$response->content = '';
			return;
		}
		if ($script) {
			$response->content = $page;
			return;
		}

		$page = preg_replace_callback("#href=\"([^\"]+?)\"#is", function($matches) {
			$s = self::replace($matches);
			return "href=\"$s\"";
		}, $page);

		$page = preg_replace_callback("#src=\"([^\"]+?)\"#is", function($matches) {
			$s = self::replace($matches);
			return "src=\"$s\"";
		}, $page);

		$response->content = $page;
		$response->send();
	}

	private static function replace($matches) {
		$base = 'https://' . \Yii::$app->params['partnerDomain'];
		if (strpos($matches[1], '/assets/') === 0) {
			$s = $base . '/site/transform-to-https?script=1&url=http://' . \Yii::$app->params['mainDomain'] . $matches[1];
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