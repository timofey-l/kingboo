<?php
namespace frontend\controllers;

use common\models\Page;
use frontend\components\HotelUrlRule;
use Yii;
use \DateTime;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Hotel;
use yii\base\InvalidParamException;
use yii\base\Theme;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\BookingParams;

/**
 * Site controller
 */
class SiteController extends Controller
{
    var $_hotel = null;

    var $themeName = "yellowKingBoo";

    var $layout = "content";


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        if (HotelUrlRule::$current !== null) {
            $this->_hotel = HotelUrlRule::$current;
            if (!is_null($this->_hotel) && !$this->_hotel->published()) {
                throw new \yii\web\HttpException(404, 'The requested hotel is not published.');
            }
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

    public function actionIndex()
    {
        $this->layout = 'main';

        // поиск страницы с роутом "/"
        $page = Page::findOne(['route' => '/']);
        if ($page !== null) {
            return $this->render('@frontend/views/pages/index', ['page' => $page]);
        } else {
            return $this->render('index');
        }

    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        if ($this->_hotel !== null) {
            $this->layout = 'main';
        }

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $email = Yii::$app->params['adminEmail'];
            if ($this->_hotel !== null) {
                $email = $this->_hotel->partner->email;
            }
            if ($model->sendEmail($email)) {
                Yii::$app->session->setFlash('success', \Yii::t('frontend', 'Thank you for contacting us. We will respond to you as soon as possible.'));
            } else {
                Yii::$app->session->setFlash('error', \Yii::t('frontend', 'There was an error sending email.'));
            }


            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
                'hotel' => $this->_hotel,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
