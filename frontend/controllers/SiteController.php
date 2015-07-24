<?php
namespace frontend\controllers;

use Yii;
use \DateTime;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Hotel;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\BookingParams;
/**
 * Site controller
 */
class SiteController extends Controller
{
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
    
    public function _beforeAction($action) {
        parent::beforeAction($action);
        if (preg_match('@(?<host>https?)://(?<domain>.+)@', \Yii::$app->request->hostInfo, $m)) {
            if ($m['domain'] == 'abc.itdesign.ru' && $action->id == 'index') {
                
                $model = Hotel::findOne(['name' => 'loceanica']);
    
                if (is_null($model)) {
                    throw new \yii\web\HttpException(404, 'The requested hotel does not exist.');
                }
                $req = \Yii::$app->request;
                $dateFrom = new DateTime($req->get('dateFrom', date('Y-m-d')));
                $dateTo = new DateTime($req->get('dateTo', date('Y-m-d')));

                $now = new DateTime();

                // проверяем, если переданная дата_с меньше сегодняшней, то устанавливаем на сегодня
                if ($now->diff($dateFrom)->invert) {
                    $dateFrom = $now;
                }

                // проверяем дата_по
                if ($dateFrom->diff($dateTo)->invert) {
                    $dateTo = clone $dateFrom;
                    $dateTo->add(new \DateInterval('P7D'));
                }

                $adults = (int)$req->get('adults', 1);
                $children = (int)$req->get('children', 0);
                $kids = (int)$req->get('kids', 0);

                $bookParams = new BookingParams([
                    'dateFrom' => $dateFrom->format('Y-m-d'),
                    'dateTo'   => $dateTo->format('Y-m-d'),
                    'adults'   => in_array($adults, range(1, 10)) ? $adults : 1,
                    'children' => in_array($children, range(1, 5)) ? $children : 0,
                    'kids'     => in_array($kids, range(1, 5)) ? $kids : 0,
                ]);

                return $this->render('@frontend/views/hotel/index', [
                    'model'      => $model,
                    'bookParams' => $bookParams,
                ]);
            }
        }
        
        return true;
    }

    public function actionIndex()
    {
        return $this->render('index');
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
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', \Yii::t('frontend', 'Thank you for contacting us. We will respond to you as soon as possible.'));
            } else {
                Yii::$app->session->setFlash('error', \Yii::t('frontend', 'There was an error sending email.'));
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
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
