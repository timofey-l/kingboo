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

}