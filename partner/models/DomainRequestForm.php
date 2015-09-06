<?php

namespace partner\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class DomainRequestForm extends Model
{
    public $name;
    public $email;
    public $domain_exists;
    public $domain;
    public $notes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'domain'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('domain-request', 'Contact person'),
            'email' => \Yii::t('domain-request', 'Contact e-mail'),
            'domain_exists' => \Yii::t('domain-request', 'Check this if you already have the domain'),
            'domain' => \Yii::t('domain-request', 'Domain'),
            'notes' => \Yii::t('domain-request', 'Notes'),
        ];
    }

    public function attributePopover($key=false)
    {
        $a = [
            'domain' => Yii::t('domain-request', 'Type the domain name you wish'),
        ];
        if ($key) {
            if (isset($a[$key])) {
                return $a[$key];
            } else {
                return '';
            }
        } else {
            return $a;
        }
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email, $hotel)
    {
        $subject = \Yii::t('domain-request', 'Request for the hotel domain from {site}', ['site' => \Yii::$app->params['mainDomain']]);
        $body = \Yii::t('domain-request', 'Contact person') . ': ' . $this->name . "\n";
        $body = \Yii::t('domain-request', 'Email') . ': ' . $this->email . "\n";
        $body = \Yii::t('domain-request', 'Partner') . ': ' . $hotel->partner->email . "\n";
        $body = \Yii::t('domain-request', 'Hotel') . ': ' . $hotel->name . "\n";
        $body .= \Yii::t('domain-request', 'Domain') . ': ' . $this->domain . "\n";
        $body .= \Yii::t('domain-request', 'Notes') . ': ' . $this->notes . "\n";
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($subject)
            ->setTextBody($body)
            ->send();
    }
}
