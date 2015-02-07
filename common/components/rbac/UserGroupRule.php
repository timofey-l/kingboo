<?php

namespace common\components\rbac;

use common\models\User;
use Yii;
use yii\rbac\Rule;
use yii\helpers\ArrayHelper;

class UserGroupRule extends Rule
{
    public $name = 'userRole';

    public function execute($user, $item, $params)
    {
        // получаем объект пользователя из базы
        //$user = ArrayHelper::getValue($params, 'user', User::findOne($user));
        $group = Yii::$app->user->identity->group;
        if (!Yii::$app->user->isGuest) {
            if ($item->name === 'admin') {
                return $group === User::GROUP_ADMIN;
            } elseif($item->name === 'client') {
                return $group === User::GROUP_ADMIN
                    || $group === User::GROUP_CLIENT;
            }
        }

        return false;
    }
}