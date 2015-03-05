<?php
namespace backend\models;

use Yii;
use partner\models\PartnerUser;
use yii\base\Model;

class CreatePartnerForm extends Model
{

    public $id;
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\partner\models\PartnerUser', 'message' => Yii::t('backend_models', 'This username already been taken')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\partner\models\PartnerUser', 'message' => Yii::t('backend_models', 'This email address already been taken')],

            ['password', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 6, 'on' => 'create'],
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['email', 'username', 'password'],
            'update' => ['email', 'username', 'password'],
        ];
    }

    /**
     * Создает учетную запись партнера
     *
     * @return null|PartnerUser
     */
    public function createPartner()
    {
        if ($this->validate()) {
            $partner = new PartnerUser();
            $partner->username = $this->username;
            $partner->email = $this->email;
            $partner->setPassword($this->password);
            $partner->generateAuthKey(); // for "remember me"
            if ($partner->save()){
                return $partner;
            }
        }

        return null;
    }

}