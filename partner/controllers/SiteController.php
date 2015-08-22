<?php
namespace partner\controllers;

use common\components\PrimaApi;
use common\models\Hotel;
use common\models\Order;
use common\models\PayMethod;
use common\models\SupportMessage;
use partner\models\PartnerUser;
use partner\models\PrimaRegForm;
use partner\models\ProfileForm;
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
                        'actions' => ['login', 'error', 'signup', 'request-password-reset', 'reset-password', 'confirm-email', 'resend-cofirm-email'],
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

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return true;
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        self::checkYandexKassa();
        $partner = PartnerUser::findOne(\Yii::$app->user->id);
        $hotels = $partner->hotels;
        $orders = Order::find()
            ->joinWith('hotel.partner')
            ->orderBy(['created_at' => SORT_DESC])
            ->where(['partner_user.id' => \Yii::$app->user->id])
            ->limit(5)
            ->all();

        $messages = SupportMessage::find()
            ->where([
                'author' => \Yii::$app->user->id,
                'parent_id' => null
            ])
            ->orderBy(['updated_at' => SORT_DESC])
            ->limit(5)
            ->all();

        $newMessagesCount = SupportMessage::find()
            ->joinWith(['parent' => function ($q) {
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
            $user = $model->getUser();
            if ($user && !$user->checked) {
                Yii::$app->session->setFlash('warning', \Yii::t('partner_login', 'You shoould confirm email address to continue'));
            }
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
                /** @var PartnerUser $user */
                $user->sendConfirmEmail();
                $hash = md5($user->email.$user->password_hash);
                $url = "https://partner.king-boo.com/site/resend-confirm-email?hash=" . $hash;
                \Yii::$app->getSession()->setFlash('success', \Yii::t('partner_login', 'Confirmation code was sent to your email. <br><a href="{url}" class="btn btn-link">Resend</a>', ['url' => $url]));
                return $this->goHome();
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

    public function actionRegisterVoip() {

    }

    public function actionProfile()
    {
        $user = PartnerUser::findOne(\Yii::$app->user->id);
        $model = new ProfileForm();
        $model->payMethods = $user->payMethods;
        $model->scid = $user->scid;
        $model->shopId = $user->shopId;
        $model->shopPassword = $user->shopPassword;
        $model->allow_checkin_fullpay = $user->allow_checkin_fullpay;
        $model->allow_payment_via_bank_transfer = $user->allow_payment_via_bank_transfer;

        if ($model->company_name != '') {
            $primaReg = new PrimaRegForm();
            if ($primaReg->load(\Yii::$app->request->post()) && \Yii::$app->request->isAjax) {

            };

            $primaReg->name = $user->company_name;
            $primaReg->phone = $user->phone;
        }

        if ($model->load(\Yii::$app->request->post())) {
            $user->scid = $model->scid;
            $user->shopId = $model->shopId;
            $user->shopPassword = $model->shopPassword;
            $user->allow_checkin_fullpay = $model->allow_checkin_fullpay;
            $user->allow_payment_via_bank_transfer = $model->allow_payment_via_bank_transfer;

            // password
            if ($model->password !== '') {
                $user->password = $model->password;
            }

            if (count($model->payMethods)) {
                // удаляем имеющиеся методы оплаты
                foreach ($user->payMethods as $payMethod) {
                    $user->unlink('payMethods', $payMethod);
                }

                // добавляем отмеченные
                if ($model->payMethods) {
                    foreach ($model->payMethods as $payMethod) {
                        $user->link('payMethods', PayMethod::findOne([(int)$payMethod]));
                    }
                }
            }
            if ($user->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('partner_profile', 'Profile successfully saved'));
                return $this->redirect('/');
            };
        }

        return $this->render('profile', [
            'partner' => PartnerUser::findOne(\Yii::$app->user->id),
            'user' => $model,
            'primaUser' => $user,
            'primaReg' => isset($primaReg) ? $primaReg : null,
        ]);
    }

    public function actionConfirmEmail($code)
    {
        $query = new \yii\db\Query;
        $query->select('id')
            ->from('{{%partner_user}}')
            ->where(['MD5(CONCAT(password_hash,created_at))' => $code, 'checked' => 0]);
        $user_code = $query->one();
        $user = PartnerUser::findOne($user_code['id']);
        $user->checked = 1;
        $user->save();
        if ($user_code) {
            if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
                return $this->redirect('/');
            }
        } else {
            \Yii::$app->session->setFlash('warning', \Yii::t('partner_login', 'User not found'));
            return $this->redirect(['site/login']);
        }
    }

    public function actionResendConfirmEmail($hash)
    {
        $query = new \yii\db\Query;
        $user_id = $query->select('id')
            ->from('{{%partner_user}}')
            ->where(['MD5(CONCAT(email, password_hash))' => $hash])
            ->one();
        $user = PartnerUser::findOne($user_id['id']);
        if ($user) {
            /** @var PartnerUser $user */
            $user->sendConfirmEmail();
            $hash = md5($user->email.$user->password_hash);
            $url = "https://partner.king-boo.com/site/resend-confirm-email?hash=" . $hash;
            \Yii::$app->getSession()->setFlash('success', \Yii::t('partner_login', 'Confirmation code was sent to your email. <br><a href="{url}" class="btn btn-link">Resend</a>', ['url' => $url]));
        } else {
            \Yii::$app->session->setFlash('warning', \Yii::t('partner_login', 'User not found!'));
        }
        return $this->redirect('login');
    }

    public static function checkYandexKassa()
    {
        return;
        /** @var PartnerUser $partner */
        $partner = PartnerUser::findOne(\Yii::$app->user->id);
        if (trim($partner->shopPassword) == ''
            || trim($partner->shopId) == ''
            || trim($partner->scid) == ''
        ) {
            \Yii::$app->session->setFlash('warning', \Yii::t('partner', '<b>Yandex.Money is not configured.</b><br>Your customers can not make online payments.<br>For integration with Yandex.Money, please, enter the required settings on the <a href="/profile">Profile page</a>.'));

            // если еще и не включена "Позволить полную оплату при заселении"
            // сообщаем о неработоспособности системы бронирования
            if (!$partner->allow_checkin_fullpay && !$partner->allow_payment_via_bank_transfer) {
                \Yii::$app->session->setFlash('danger', \Yii::t('partner', '<b>Your clients can not make reservation!</b><br>Payment at check in and payment via bank transfer are not active, and integration with Yandex.Money is not configured.<br>You can activate the required options in <a href="/profile">Profile</a>'));
            }
        }
    }
}
