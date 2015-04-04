<?php
namespace console\controllers;

use common\models\Hotel;
use Faker\Factory;
use partner\models\PartnerUser;
use Yii;
use yii\console\Controller;
use backend\models\BackendUser;
use yii\helpers\Inflector;

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

    public function actionGenerateFake() {
        $faker_ru = Factory::create('ru_RU');
        $faker_en = Factory::create();

        //delete existing partners
        foreach (PartnerUser::find()->all() as $partner) {
            $partner->delete();
        }

        foreach (range(1,5) as $i) {

            $partner = new PartnerUser();
            $partner->username = 'partner' . $i;
            $partner->email = $partner->username . '@testdomain.com';
            $partner->password = "partner";
            print_r($partner->attributes);
            if ($partner->save()) {
                foreach (range(1, 3) as $j) {
                    $hotel = new Hotel();
                    $hotel->partner_id = $partner->id;
                    $hotel->name = 'testHotel' . $i . $j;
                    $hotel->title_ru = $faker_ru->company;
                    $hotel->title_en = transliterator_transliterate('Any-Latin; Latin-ASCII', $hotel->title_ru);
                    $hotel->address = $faker_en->address;
                    $hotel->description_en = $faker_en->text;
                    $hotel->description_ru = $faker_ru->text;
                    $hotel->lat = 0;
                    $hotel->lng = 0;
                    $hotel->category = rand(1,5);
                    $hotel->timezone = '';
                    $hotel->save();
                }

            }
        }

    }
}