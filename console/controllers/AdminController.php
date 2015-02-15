<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use backend\models\BackendUser;

class AdminController extends Controller
{
    public function actionAddAdmin()
    {
        echo "Adding new admin.\n\n";

        // get email
        echo "Enter email:";
        $email = trim(fgets(STDIN));

        // get password
        echo "Enter password:";
        $password = trim(fgets(STDIN));

        // get username
        $username_def = substr($email,0,strpos('@', $email));
        if ($username_def === false) {
            echo "Enter username:";
        } else {
            echo "Enter username [{$username_def}]:";
        }
        $username = trim(fgets(STDIN));
        if ($username_def !== false && trim($username) == '') {
            $username = $username_def;
        }

        $user = new BackendUser();

        $user->email = $email;
        $user->username = $username;
        $user->password = $password;

        if ($user->save()) {
            echo "User successfully created.\n";
        } else {
            echo "Error while creating user.\n";
        }

    }
}