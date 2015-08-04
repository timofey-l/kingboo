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
 * @property \Memcache $memcachedObject
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
            $response = $this->loginAPI();
            if (is_object($response) && $response->result == 1 && property_exists($response->data, 'sid')) {
                $this->sid = $response->data->sid;
                $this->memcachedObject->add('prima_api_sid', $this->sid, false, $this->cacheSidTimeout);
            } else {
                return false;
            }
        } else {
            $this->sid = $_sid;
        }
        return true;
    }

    public function connectToMemcached()
    {
        $this->memcachedObject = new \Memcache;
        $this->memcachedObject->connect($this->memcached_host, $this->memcached_port);
    }

    public function doRequest($config)
    {
        $default_config = [
            'svc' => '',
            'data' => [],
            'api' => false,
            'sign' => false,
            'lang' => 'en',
        ];
        $config = ArrayHelper::merge($default_config, $config);

        // делаем подпись, если требуется
        if ($config['sign']) {
            $this->makeSign($config);
        }

        $url = $this->svcUrl;
        if ($config['api']) {
            $url = $this->apiUrl;
        }

        $params = '?' . http_build_query([
                'svc' => $config['svc'],
                'mode' => $this->mode,
                'lang' => $config['lang'],
            ]);

        $curl_options = [
            CURLOPT_URL => $url.$params,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $config['data'],
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        $res = curl_exec($ch);

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

        if ($result->result == 1) {
            $this->sid = $result->data->sid;
        }

        return $result;
    }

    public function makeSign(&$config)
    {

    }

}