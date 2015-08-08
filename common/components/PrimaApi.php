<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Class PrimaApi
 * Все методы для общения с примателеком
 *
 * @package common\components
 * @property \Memcached $memcachedObject
 */
class PrimaApi extends Component
{

    public $apiLogin = '';
    public $apiPassword = '';

    public $apiUrl = 'http://tp-api.primatel.ru/';
    public $svcUrl = 'http://tp-svc.primatel.ru/';
    public $mode = 'json';

    public $cacheSidTimeout = 600; //в секундах
    public $cacheRequestsTimeout = 60;

    public $sid = '';

    public $memcached_host = '127.0.0.1';
    public $memcached_port = '11211';
    private $memcachedObject = null;


    /**
     * Создание объекта
     *
     */
    public function init()
    {

        $this->connectToMemcached();

        $this->checkApiSession();

        parent::init();
    }

    public function checkApiSession()
    {
        $_sid = $this->memcachedObject->get('prima_api_sid');
        if ($_sid == false) {
            $this->loginAPI();
            $this->getSessionInfo();
        } else {
            $this->sid = $_sid;
            $this->getSessionInfo();
        }
        return true;
    }

    public function getSessionInfo()
    {
        $params = [
            'svc' => 'getSessionInfo',
            'api' => true,
        ];

        $result = $this->doRequest($params);
        if (is_null($result) || is_object($result) && property_exists($result, 'result') && $result->result == 0) {
            $this->loginAPI();
        }

        return $result;
    }

    public function connectToMemcached()
    {
        $this->memcachedObject = new \Memcached;
        $this->memcachedObject->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
        $this->memcachedObject->addServer('localhost', 11211);
//        $this->memcachedObject->connect($this->memcached_host, $this->memcached_port);
    }

    public function doRequest($config)
    {
        $default_config = [
            'svc' => '',
            'data' => [
            ],
            'api' => false,
            'sign' => false,
            'lang' => 'en',
        ];
        $config = ArrayHelper::merge($default_config, $config);


        $url = $this->svcUrl;
        if ($config['api']) {
            $url = $this->apiUrl;
        }

        $params_arr = [
            'svc' => $config['svc'],
            'mode' => isset($config['mode'])?$config['mode']:$this->mode,
            'lang' => $config['lang'],
        ];
        if ($config['svc'] != 'login' ) {
            $params_arr['sid'] = $this->sid;
            $config['data']['sid'] = $this->sid;
        }
        $params = '?' . http_build_query($params_arr);


        $curl_options = [
            CURLOPT_URL => $url.$params,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($config['data']),
//            CURLINFO_HEADER_OUT => 1,
//            CURLOPT_HTTPHEADER => [
//                'Content-Type: application/json',
//            ]
        ];

        if (!$config['api'] && $config['svc'] != 'login') {
            $curl_options[CURLOPT_COOKIE] = 'PHPSESSID='.$this->sid;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        $res = curl_exec($ch);

        curl_close($ch);


        $this->memcachedObject->touch('prima_api_sid', $this->cacheSidTimeout);

        return json_decode($res);
    }

    public function loginAPI()
    {
        $params = [
            'api' => true,
            'svc' => 'login',
            'data' => [
                'login' => $this->apiLogin,
                'password' => $this->apiPassword,
            ]
        ];

        $result = $this->doRequest($params);

        if (is_object($result) && $result->result == 1) {
            $this->sid = $result->data->sid;
        }
        $this->memcachedObject->set('prima_api_sid', $this->sid, $this->cacheSidTimeout);

        return $result;
    }


}