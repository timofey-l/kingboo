<?php
namespace common\components;
 
use \Yii;
use yii\base\Component;
use \common\models\ExchangeRates;
use \common\models\Currency;
 
class ExchangeRatesParser extends Component {

	/**
	 * Получаем курсы валют
	 * Базовая валюта: USD
	 * Служба https://currencylayer.com
	 * e-mail: admin@king-boo.com
	 * password: 3Trafalgar
	 * http://www.apilayer.net/api/live?access_key=4e2852fcbd056eb02040a92b2e2a83ca
	 */

	const PARSE_URL = 'http://www.apilayer.net/api/live';
	const ACCESS_KEY = '4e2852fcbd056eb02040a92b2e2a83ca';

	public static function parse() {
		$s = curl_init(); 

		$currencies = Currency::find()->all();

		//Составляем список валют для парсинга
		$list = [];
		foreach ($currencies as $cur) {
			if ($cur->code != 'USD') {
				$list[] = $cur->code;
			}
		}
		$list = implode(',', $list);

		$params = [
			'access_key' => self::ACCESS_KEY,
			'currencies' => $list,
		];

		$paramstr = '';
		foreach ($params as $k=>$v) {
			$paramstr .= "$k=$v&";
		}

		curl_setopt($s, CURLOPT_URL, self::PARSE_URL . '?' . $paramstr); 
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 

		$rates = curl_exec($s);
		curl_close($s);

		if (!$rates) {
			throw new \Exception("ExchangeRatesParser: no response");
		}
		$rates = json_decode($rates);
		if (!isset($rates->success) || $rates->success != 1 || !isset($rates->quotes)) {
			throw new \Exception("ExchangeRatesParser: error");
		}

		$echo = '';

		// вытаскиваем все валюты
		$r = [];
		foreach ($rates->quotes as $k => $v) {
			$currency = substr($k, 3);
			$r[$currency] = $v;
			$echo .= "USD / $currency = $v\n";
		}

		// проверка все ли валюты на месте
		foreach ($currencies as $cur) {
			if ($cur->code != 'USD') {
				if (!isset($r[$cur->code])) {
					throw new \Exception("ExchangeRatesParser: {$cur->code} not found");
				}
			}
		}

		$exr = new ExchangeRates();
		$exr->date = date('Y-m-d H:i:s');
		$exr->rates = serialize($r);
		if (!$exr->save()) {
			throw new \Exception("ExchangeRatesParser: db save error");
		} else {
			$echo .=  "ok\n";
		}

		return $echo;
	}


}