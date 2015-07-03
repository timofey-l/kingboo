<?php
namespace partner\controllers;

use common\models\Hotel;
use common\models\Order;
use common\models\SupportMessage;
use partner\models\PartnerUser;
use Yii;
use partner\models\LoginForm;
use partner\models\PasswordResetRequestForm;
use partner\models\ResetPasswordForm;
use partner\models\SignupForm;
use partner\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
        return [           //TODO: Сделать Check Access
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
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

    public function actionIndex()
    {
        $partner = PartnerUser::findOne(\Yii::$app->user->id);
        $hotels = $partner->hotels;
        $orders = Order::find()
            ->joinWith('hotel.partner')
            ->orderBy(['created_at' => SORT_DESC])
            ->where(['partner_user.id' => \Yii::$app->user->id])
            ->limit(5)
            ->all();

        $messages =SupportMessage::find()
            ->where([
                'author' => \Yii::$app->user->id,
                'parent_id' => null
            ])
            ->orderBy(['updated_at' => SORT_DESC])
            ->limit(5)
            ->all();

        $newMessagesCount = SupportMessage::find()
            ->joinWith(['parent' => function($q){
                $q->from('support_messages p');
            }])
            ->where([
                'support_messages.unread' => 1,
                'p.author' => \Yii::$app->user->id,
            ])->count();

        $newOrdersCount = Order::find()
            ->joinWith('hotel.partner')
            ->where([
                'partner_user.id' => \Yii::$app->user->id,
                'viewed' => 0,
            ])
            ->count();

        return $this->render('index', [
            'partner' => $partner,
            'hotels' => $hotels,
            'orders' => $orders,
            'messages' => $messages,
            'newOrdersCount' => $newOrdersCount,
            'newMessagesCount' => $newMessagesCount,
        ]);
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
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
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

    public function actionTest()
    {
        $hotel = \common\models\Hotels::findOne([
            'name' => 'Test hotel',
            'partner_id' => 1,
        ]);

        if ($hotel === null) {
            $hotel = new \common\models\Hotels();
            $hotel->name = 'Test hotel';
            $hotel->partner_id = 1;
            $hotel->address = "Test addess street 777";
            $hotel->lng = 1.4;
            $hotel->lat = 3.3;
            $hotel->description = "The best test hotel ever!";
            $hotel->category = 3;
            $hotel->timezone = 'Europe/Minsk';
            $hotel->save();
        }

        var_dump($hotel->food);


    }
}
