<?php
namespace partner\models;

use common\models\PayMethod;
use common\models\User;
use Yii;

class PartnerUser extends User
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%partner_user}}';
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            foreach ($this->hotels as $hotel) {
                $hotel->delete();
            };
        }
        return true;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = static::findByEmail($this->email);
        }

        return $this->_user;
    }

    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function getHotels()
    {
        return $this->hasMany('\common\models\Hotel', ['partner_id' => 'id']);
    }

    public function getPayMethods() {
        return $this->hasMany(PayMethod::className(), ['id' => 'pay_method_id'])
            ->viaTable('partner_payMethods', ['partner_id' => 'id']);
    }

    public function sendConfirmEmail() {
        \Yii::$app->mailer->compose(
            [
                'html' => 'partnerConfirmEmail-html.php',
                'text' => 'partnerConfirmEmail-text.php',
            ], [
                'link' => 'https://partner.king-boo.com/confirm-email?code=' . md5($this->password_hash.$this->created_at),
                'resendCode' => 'https://partner.king-boo.com/confirm-email?code=' . md5($this->email,$this->password_hash),
            ]
        )
            ->setFrom(\Yii::$app->params['email.from'])
            ->setTo($this->email)
            ->setSubject(\Yii::t('partner_login', 'Email confirmation for site partner.king-boo.com'))
            ->send();
    }

}