<?php
namespace frontend\components;

use common\models\Hotel;
use yii\web\ForbiddenHttpException;
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
                return "http://" . $params['name'] . '.' . $_SERVER['SERVER_NAME'];
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

        // пробуем определить частичный URL
        if ($request->serverName != \Yii::$app->params['mainDomain'])
            if (preg_match('%^(?<protocol>https?)://(?<name>[^.]+)\.' . \Yii::$app->params['mainDomain'] . '$%', $hostInfo, $m)) {
                $hotel = Hotel::find()->where(['name' => $m['name']])->one();
                if ($hotel) {

                    if ($pathInfo) {
                        //payment url
                        if (preg_match('%payment/(?<id>[0-9a-zA-Z\-_]{64})$%', $pathInfo, $m_path)) {
                            $params = [
                                'id' => $m_path['id'],
                            ];
                            return ['payment/show', $params];
                        }
                        return [$pathInfo, $request->get()];
                    }

                    $params['name'] = $hotel->name;
                    return ['hotel/index', $params];
                } else {
                    throw new ForbiddenHttpException('Hotel not found!');
                }
            } elseif (preg_match('%^(?<protocol>https?)://(?<domain>.+)$%', $hostInfo, $m)) {
                $hotel = Hotel::find()->where(['domain' => $m['domain']])->one();
                if ($hotel) {

                    if ($pathInfo) {
                        //payment url
                        if (preg_match('%payment/(?<id>[0-9a-zA-Z\-_]{64})$%', $pathInfo, $m_path)) {
                            $params = [
                                'id' => $m_path['id'],
                            ];
                            return ['payment/show', $params];
                        }
                        return [$pathInfo, $request->get()];
                    }

                    $params['name'] = $hotel->name;
                    return ['hotel/index', $params];
                } else {
                    throw new ForbiddenHttpException('Hotel not found!');
                }
            }

        return false;
    }

}