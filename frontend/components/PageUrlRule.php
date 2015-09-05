<?php
namespace frontend\components;

use common\models\Hotel;
use common\models\Page;
use yii\web\ForbiddenHttpException;
use yii\web\UrlRule;

class PageUrlRule extends UrlRule
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
        if ($route === 'pages/index') {
            $page = null;
            if (isset($params['id']) && is_int((int)$params['id'])) {
                $page = Page::findOne($params['id']);
            }
            if (isset($params['route'])) {
                $page = Page::findOne(['route' => $params['route']]);
            }
            if (is_null($page)) {
                return false;
            }

            return $page->route;
        }
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $hostInfo = $request->getHostInfo();
        $pathInfo = $request->pathInfo;

        // $request->_lang_url
        // $request->_url

        if (preg_match('~(?<path>.*)\/$~',$pathInfo,$m)) {
            $pathInfo = $m['path'];
        }

        $page = Page::findOne(['route' => $pathInfo]);

        if ($page !== null) {
            Page::setCurrent($page);
            if ($page->route == '/') {
                Page::setMain(true);
            }
            return ['pages/index', ['route' => $page->route]];
        }

        return false;
    }

}