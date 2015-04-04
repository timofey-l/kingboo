<?php
namespace partner\models;

use Yii;
use common\models\User;

class PartnerUser extends User{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%partner_user}}';
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = static::findByEmail($this->email);
        }

        return $this->_user;
    }

    public static function getHotels() {
        return static::hasMany('\common\models\Hotels', ['partner_id' => 'id']);
    }

    public static function findByEmail($email) {
        return static::findOne([
            'email' => $email,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

}