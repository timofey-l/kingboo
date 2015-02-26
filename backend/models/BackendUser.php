<?php
namespace backend\models;

use Yii;
use common\models\User;

class BackendUser extends User{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%backend_user}}';
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = static::findByEmail($this->email);
        }

        return $this->_user;
    }

    public static function findByEmail($email) {
        return static::findOne([
            'email' => $email,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

}
