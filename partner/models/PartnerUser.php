<?php
namespace partner\models;

use common\models\BillingAccount;
use common\models\BillingAccountServices;
use common\models\BillingService;
use common\models\Currency;
use common\models\PayMethod;
use common\models\User;
use Yii;

/**
 * Class PartnerUser
 * @package partner\models
 *
 * @property boolean $yandex_demo
 * @property string $shopId
 * @property string $scid
 * @property string $shopPassword
 *
 * @property integer $allow_checkin_full_pay
 * @property integer $allow_payment_via_bank_transfer
 *
 * @property boolean $private_person
 * @property string  $company_name
 * @property string  $phone
 * @property string  $prima_login
 * @property string  $demo_expire
 * 
 * @property text  $system_info
 */
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

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        // создание аккаунта в биллинге
        if ($insert) {
            $billingAccount = new BillingAccount;
            $billingAccount->partner_id = $this->id;
            $billingAccount->currency_id = Currency::findOne(['code' => 'RUB'])->id;
            $billingAccount->save();
        }

        // Сигнал для системы сообщений
        if (isset(\Yii::$app->automaticSystemMessages)) {
            \Yii::$app->automaticSystemMessages->setDataUpdated();
        }
    }

    public function getBilling()
    {
        return $this->hasOne(\common\models\BillingAccount::className(), ['partner_id' => 'id']);
    }

    public function getAccount()
    {
        return $this->hasOne(\common\models\BillingAccount::className(), ['partner_id' => 'id']);
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
        return $this->hasMany(\common\models\Hotel::className(), ['partner_id' => 'id']);
    }

    /**
     * Сейчас на самом деле аккаунт может быть только один, это ограничено в BillingAccount->rules + unique index
     */
    public function getAccounts()
    {
        return $this->hasMany(\common\models\BillingAccount::className(), ['partner_id' => 'id']);
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

    public function sendConfirmEmailWithPassword($password) {
        \Yii::$app->mailer->compose(
            [
                'html' => 'partnerConfirmEmailWithPassword-html.php',
                'text' => 'partnerConfirmEmailWithPassword-text.php',
            ], [
                'link' => 'https://partner.king-boo.com/confirm-email?code=' . md5($this->password_hash.$this->created_at),
                'resendCode' => 'https://partner.king-boo.com/confirm-email?code=' . md5($this->email,$this->password_hash),
                'password' => $password,
                'email' => $this->email,
            ]
        )
            ->setFrom(\Yii::$app->params['email.from'])
            ->setTo($this->email)
            ->setSubject(\Yii::t('partner_login', 'Email confirmation for site partner.king-boo.com'))
            ->send();
    }

    /**
     * Возвращет true если пробный период закончен. False в противном случае
     * @return int
     */
    public function getDemoExpired() {
        return (new \DateTime())->diff(new \DateTime($this->demo_expire))->invert;
    }

    /**
     * Возвращает количество дней между сегодняшней датой и датой истечения пробного периода
     * false - если срок прошел
     * @return mixed
     */
    public function getDemoLeft() {
        return !$this->getDemoExpired() ? (new \DateTime())->diff(new \DateTime($this->demo_expire))->days : false;
    }

    /**
     * Возвращает true, если аккаунт заблокирован, например по причине отрицательного баланса
     * @return boolean
     */
    public function isBlocked() {
        // учет ситуации, когда демо период закончен, а платеж не поступил
        $demoDate = \DateTime::createFromFormat('Y-m-d', $this->demo_expire);
        $now = new \DateTime();
        $income = \common\models\BillingIncome::find(['account_id' => $this->accounts[0]->id])->orderBy('date ASC')->one();
        
        if ($this->billing->balance < -\Yii::$app->params['partner.credit'] || ($demoDate < $now && !$income)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Возвращает дату с которой начинать списывание денег с аккаунта 
     * (максимум из конца тестового периода и даты первого платежа) 
     * 
     * @return DateTime
     */
    public function getActivationDate() {
        $demoDate = \DateTime::createFromFormat('Y-m-d', $this->demo_expire);
        $income = \common\models\BillingIncome::find()->where(['account_id' => $this->accounts[0]->id])->orderBy('date ASC')->one();
        if (!$income) {
            return false;
        }
        $incomeDate = \DateTime::createFromFormat('Y-m-d H:i:s', $income->date);
        if ($demoDate > $incomeDate) {
            return $demoDate;
        } else {
            return $incomeDate;
        }
    }
     
}
