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

}