<?php
namespace partner\models;

use common\components\MailerHelper;
use partner\models\PartnerUser;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\partner\models\PartnerUser', 'message' => \Yii::t('partner_login', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new PartnerUser();
            $user->username = $this->email;
            $user->email = $this->email;
            $user->demo_expire = date('Y-m-d', time() + 86400 * \Yii::$app->params['partner.demo']);
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                // send email to admins
                MailerHelper::adminEmail('Регистрация нового пользователя', "<pre>".var_export($user->attributes, true)."</pre>", 'report');
                return $user;
            }
        }

        return null;
    }

    public function attributeLabels() {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }
}
