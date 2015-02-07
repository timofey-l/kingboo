<?php
namespace console\controllers;

use common\components\rbac\UserGroupRule;
use Yii;
use yii\rbac\Permission;
use yii\rbac\PhpManager;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {

        /** @var PhpManager $auth */
        $auth = Yii::$app->authManager;
        // чистим перед определением
        $auth->removeAll();

        // права на доступ к бекэнду
        /** @var Permission $backend */
        $backend = $auth->createPermission('backend');
        $backend->description = "Backend access.";
        $auth->add($backend);

        // правило по группе пользователя
        $rule = new UserGroupRule();
        $auth->add($rule);

        // роль клиента
        $client = $auth->createRole('client');
        $client->description = "Registered client";
        $client->ruleName = $rule->name;
        $auth->add($client);

        // роль администратора
        $admin = $auth->createRole('admin');
        $admin->description = 'Application developers group';
        $admin->ruleName = $rule->name;
        $auth->add($admin);

        $auth->addChild($admin, $client);
        $auth->addChild($admin, $backend);
    }
}