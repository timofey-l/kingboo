<?php
namespace frontend\controllers;

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
    
    public function beforeAction($action) {
        parent::beforeAction($action);
        if (HotelUrlRule::$current !== null) {
            $this->_hotel = HotelUrlRule::$current;
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

    /**
     * Вывод реквизитов фирмы на печать для оплаты безналом
     * @param string $number - номер заказа (например, 19f105e0c7347f48c0dd452eb4bd165e)
     */
    public function actionBookingInvoice($number) 
    {
        if (!$order = \common\models\Order::findOne(['number' => $number])) {
            throw new NotFoundHttpException("Order #$number not found");
        }

        if (!$order->hotel->partner->allow_payment_via_bank_transfer) {
            throw new NotFoundHttpException("Order #$number not found");
        }

        $paymentDetails = new \partner\models\partnerPaymentDetailsRus();
        $paymentDetails->unpack($order->hotel->partner->payment_details);
        return $this->render('@common/views/invoices/clientBookingPrint', [
            'order' => $order,
            'paymentDetails' => $paymentDetails,
        ]);
    }
}
