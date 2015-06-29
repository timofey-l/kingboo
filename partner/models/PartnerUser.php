<?php
namespace partner\models;

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

}