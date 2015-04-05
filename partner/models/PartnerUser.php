<?php
namespace partner\models;

use Yii;
use common\models\User;

class PartnerUser extends User{

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            foreach ($this->hotels as $hotel) {
                $hotel->delete();
            };
        }
        return true;
    }

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

    public function getHotels() {
        return $this->hasMany('\common\models\Hotel', ['partner_id' => 'id']);
    }

    public static function findByEmail($email) {
        return static::findOne([
            'email' => $email,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

}