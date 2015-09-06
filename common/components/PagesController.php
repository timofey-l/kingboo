<?php
namespace common\components;

use common\models\Page;
use frontend\components\HotelUrlRule;
use yii\base\Theme;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class PagesController extends Controller
{

    public $defaultAction = 'index';
    public $layout = 'content';
    var $themeName = "yellowKingBoo";

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        if (HotelUrlRule::$current !== null) {
            $this->_hotel = HotelUrlRule::$current;
        }

        if (HotelUrlRule::$mainDomain && \Yii::$app->params['mainDomainTheme'] !== false) {
            $themePath = '@app/themes/' . \Yii::$app->params['mainDomainTheme'];

            $baseUrl = '@frontend/web';
            if (file_exists(\Yii::getAlias($themePath).'/assets')) {
                $baseUrl = \Yii::$app->assetManager->publish(\Yii::getAlias($themePath).'/assets')[1];
            }
            \Yii::$app->view->theme = new Theme([
                'basePath' => '@app/themes/' . \Yii::$app->params['mainDomainTheme'],
                'baseUrl' => $baseUrl,
                'pathMap' => [
                    '@app/views' => '@app/themes/' . \Yii::$app->params['mainDomainTheme'],
                ],
            ]);
        }

        return true;
    }


    public function actionIndex($route) {

        $page = Page::findOne(['route' => $route]);

        if ($page === null) {
            throw new BadRequestHttpException('Page not found!');
        }

        // проврка наличия "/" на конце
        if (preg_match('~\/$~', \Yii::$app->request->pathInfo)) {
            return $this->redirect([\Yii::$app->request->pathInfo]);
        }

        return $this->render('index', [
            'page' => $page,
        ]);
    }

}