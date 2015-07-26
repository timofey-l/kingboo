<?php
namespace frontend\components;

use common\models\Hotel;
use yii\web\UrlRule;

class HotelUrlRule extends UrlRule
{
    public $connectionID = 'db';

    public function init()
    {
        if ($this->name === null) {
            $this->name = __CLASS__;
        }
    }

    public function createUrl($manager, $route, $params)
    {
        if ($route === 'hotel/view') {
            if (isset($params['name'])) {
                return "http://" . $params['name'] . '.' .  $_SERVER['SERVER_NAME'];
            } else {
                return false;
            }
        }
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $hostInfo = $request->getHostInfo();
        $pathInfo = $request->pathInfo;

        if ($pathInfo) {
            return [$pathInfo, $request->get()];
        }

        // пробуем определить частичный URL
        if (preg_match('%^(?<protocol>https?)://(?<name>[^.]+)\..+$%', $hostInfo, $m)) {
            $hotel = Hotel::find()->where(['name' => $m['name']])->one();
            if ($hotel) {
                $params['name'] = $hotel->name;
                return ['hotel/index', $params];
            } else {
                return false;
            }
        }

        // пробуем найти домен
        if (preg_match('%^(?<protocol>https?)://(?<domain>.+)$%', $hostInfo, $m)) {
            $hotel = Hotel::find()->where(['domain' => $m['domain']])->one();
            if ($hotel) {
                $params['name'] = $hotel->name;
                return ['hotel/index', $params];
            } else {
                return false;
            }
        }

        return false;
    }

}