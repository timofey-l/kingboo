<?php
namespace console\controllers;

use common\components\BookingHelper;
use common\components\MailerHelper;
use common\models\BillingAccount;
use common\models\BillingAccountServices;
use common\models\BillingExpense;
use common\models\BillingService;
use common\models\Hotel;
use common\models\Order;
use common\models\Room;
use DateTime;
use partner\models\PartnerUser;
use Yii;
use yii\console\Controller;
use backend\models\BackendUser;
use yii\console\Exception;
use yii\helpers\Console;

class AdminController extends Controller
{

    public function actionDo() {
        $data = [
            'login' => 'tweedledum',
            'name' => 'ИТ Дизайн',
            'phone' => '79213125551',
            'email' => 'office@itdesign.ru',
        ];
        $res = \Yii::$app->primaApi->userRegistration($data);
        print_r($res); echo "\n";
    }

    public function actionChangeAdminPassword($id)
    {
        /** @var BackendUser $admin */
        $admin = BackendUser::findOne($id);
        if (!$admin) {
            throw new Exception('User not found');
        }

        echo "User id: {$admin->id} \n";
        echo "User email: {$admin->email} \n";
        echo "User name: {$admin->username} \n";

        echo "Enter new user password (Ctrl + C to exit): ";
        $password = trim(fgets(STDIN));
        $admin->password = $password;
        $admin->generateAuthKey();
        if ($admin->save()) {
            echo "\nPassword updated.\n";
        } else {
            echo "\nError while updating password\n";
        }
    }

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
        $user->generateAuthKey();

        if ($user->save()) {
            echo "User successfully created.\n";
        } else {
            echo "Error while creating user.\n";
        }

    }

    /**
     * Парсинг курсов валют относительно USD (USD / валюта)
     */
    public function actionExchangeRates($echo = false) {
        $res = \common\components\ExchangeRatesParser::parse();
        if ($echo) {
            echo $res;
        }
    }
    
    /**
     * Рассылка писем с предупреждениями о необходимости пополнения баланса 
     */
    public function actionPartnerBalanceInfo() {
        $report = MailerHelper::partnerBalanceInfo();
        MailerHelper::adminEmail('Balance mails to partners', '<pre>' . $report . '</pre>', 'report');
        echo $report;
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
                    $hotel->address_ru = $faker_ru->address;
                    $hotel->address_en = $faker_en->address;
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

    public function actionGenerateLoceanica() {
        //delete existing partners
        foreach (PartnerUser::find()->where(['username' => 'L\'Oceanica', 'email' => 'office@itdesign.ru'])->all() as $partner) {
            $partner->delete();
        }

        $partner = new PartnerUser();
        $partner->username = 'L\'Oceanica';
        $partner->email = 'office@itdesign.ru';
        $partner->password = "loceanica";
        $partner->checked = true;
        $partner->generateAuthKey();
        print_r($partner->attributes);
        if ($partner->save()) {
            $hotel = new Hotel();
            $hotel->partner_id = $partner->id;
            $hotel->name = 'loceanica';
            $hotel->title_ru = 'L\'Oceanica Beach Resort Hotel';
            $hotel->title_en = 'L\'Oceanica Beach Resort Hotel';
            $hotel->address_ru = 'Camyuva Mah. 5087 Sok. No: 10/1, Kemer, Antalya/Turkey';
            $hotel->address_en = 'Camyuva Mah. 5087 Sok. No: 10/1, Kemer, Antalya/Turkey';
            $hotel->description_en = 'Welcome to L’Oceanica Beach Resort Hotel, a magnificent five star seaside resort in Kemer\'s bright blue-green Mediterranean coast of Camyuva Bay of Antalya. Our management team have more than 25 years of experience in providing all-inclusive service at five star excellence and unbeatable affordability. L\'Oceanica Hotel is unique in its kind and strives to be all- a family hotel, budget hotel, and five star hotel- with all-included meals and drinks, all included first class animation and fun, and a wellness center and turkish bath or hamam for the ultimate relaxation and spa holiday. You will swim in the same sea as the ancient Greeks and Romans and wake up each day to mother nature untouched. Leave us to think of the rest for you and your family\'s perfect holiday.';
            $hotel->description_ru = 'У подножия Таврских гор, в сени высоких сосен, олеандр и апельсиновых деревьев, на самом берегу Средиземного моря расположился пятизвездочный отель L’Oceanica Beach Resort. Оформление, навеянное архитектурой Монреаля прошлого века, высококлассная кухня и поистине домашняя атмосфера с запахом турецкого кофе и гранатового сока сделают Ваш отдых незабываемым!';
            $hotel->lat = 0;
            $hotel->lng = 0;
            $hotel->category = 5;
            $hotel->timezone = '';
            $hotel->currency_id = 2;

            if ($hotel->save()) {
                print_r($hotel->attributes);
                $room = new Room();
                $room->hotel_id = $hotel->id;
                $room->title_en = 'Annex Building Standard Room';
                $room->title_ru = 'Корпус Аннекс: стандартный номер';
                $room->description_en = '';
                $room->description_ru = 'Просторный номер, оформленный в теплых тонах с использованием натуральных оттенков, оборудован современной мебелью и свежим ламинатным покрытием. Санузел оснащен ванной и комплектом качественных туалетных принадлежностей. Два номера специально подготовлены для гостей с ограниченными физическими возможностями.';
                $room->adults = 3;
                $room->children = 2;
                $room->total = 3;
                $room->active = 1;
                $room->price_type = 2;
                $room->amount = 10;
                $room->save();

                $room = new Room();
                $room->hotel_id = $hotel->id;
                $room->title_en = 'Main Building Standard Room';
                $room->title_ru = 'Главный корпус: стандартный номер';
                $room->description_en = 'These elegant rooms offer spacious and comfortable accommodation ideal for couples and families. Two of our Standard rooms are disability friendly.';
                $room->description_ru = 'Небольшой номер, оформленный в теплых тонах с использованием натуральных оттенков, оборудован современной мебелью и свежим ламинатным покрытием. Санузел оснащен душем и комплектом качественных туалетных принадлежностей.';
                $room->adults = 3;
                $room->children = 2;
                $room->total = 4;
                $room->active = 1;
                $room->price_type = 2;
                $room->amount = 10;
                $room->save();

                $room = new Room();
                $room->hotel_id = $hotel->id;
                $room->title_en = 'Main Building Family Room';
                $room->title_ru = 'Главный корпус: семейный номер';
                $room->description_en = 'The Family Room consists of 2 rooms and 1 bathroom with a shower. Large families or friends travelling together will appreciate these rooms with a connecting door.';
                $room->description_ru = 'Построенные в 2015 году семейные номера состоят из 2 комнат и 1 ванной комнаты, оборудованной душевой кабиной. Свежий ремонт, новая мебель и современная сантехника — оцените их первыми!';
                $room->adults = 3;
                $room->children = 2;
                $room->total = 4;
                $room->active = 1;
                $room->price_type = 1;
                $room->amount = 10;
                $room->save();

                $room = new Room();
                $room->hotel_id = $hotel->id;
                $room->title_en = 'Villa';
                $room->title_ru = 'Вилла';
                $room->description_en = '';
                $room->description_ru = 'Наши виллы расположены в 15 минутах пешей прогулки от корпусов отеля (примерно 800 метров) и представляют собой расположенные рядом шесть двухэтажных коттеджей. Гости питаются в ресторанах и барах главного корпуса отеля, также при желании могут приготовить еду самостоятельно у себя на кухне. Пляж отеля и бар на пляже – к услугам наших гостей. При необходимости организовывается бесплатный трансфер в отель и обратно.';
                $room->adults = 6;
                $room->children = 3;
                $room->total = 6;
                $room->active = 1;
                $room->price_type = 1;
                $room->amount = 10;
                $room->save();
            } else {
                print_r($hotel->errors);
            }
        }

    }

    /**
     * Списание средств в соответствии с тарифами
     * @param bool|false $showDebugInfo
     */
    public function actionExpensesUpdate($showDebugInfo = false)
    {
        $log = BillingExpense::processExpensesNew();

        MailerHelper::adminEmail('Ежедневное списание средств по активным тарифам', "<pre>{$log}</pre>", 'report');
        echo $log;
    }

    public function actionTest($id = 1) {
        /** @var BillingAccount $account */
        $account = BillingAccount::findOne($id);
        $account->updateBalance();
        var_dump($account->balance);
	}

    public function actionAddDefaultPlans()
    {
        $this->stdout("Начало добавления тарифа по умолчанию к аккаунтам биллинга партнёров.\n");

        $default_plan = BillingService::findOne(['default' => true]);
        if ($default_plan === null) {
            $this->stderr("Не найден тарифный план по умолчанию\n");
        } else {
            $this->stdout("Используется тарифный план \"{$default_plan->name_ru}\" id: {$default_plan->id} \n");
        }

        $accounts = BillingAccount::find()->all();

        foreach ($accounts as $account) {
            $this->stdout("\tАккаунт {$account->id}\n");
            /** @var BillingAccount $account */
            $services = $account->services;
            $this->stdout("\tНайдено тарифов: " . count($services) . "\n");
            if (count($services) == 0) {
                $accountService = new BillingAccountServices();
                $accountService->account_id = $account->id;
                $accountService->service_id = $default_plan->id;
                $accountService->add_date = date(DateTime::ISO8601);
                $accountService->end_date = date(DateTime::ISO8601);
                $accountService->active = true;
                if (!$accountService->save()) {
        		    var_dump($accountService->errors);
        		}
            }
            $this->stdout("\n");
        }
    }

    public function actionTestEmails()
    {
        foreach (Order::find()->all() as $order) {
            /** @var Order $order */
            $this->stdout('id: ' . $order->id . "\t" . $order->contact_email . "\t" . $order->hotel->partner->email."\n");
        }

        $id = $this->prompt("Enter id:", []);

        $order = Order::findOne($id);

        $this->stdout("Sending email to client\n");
        $order->sendEmailToClient();
        $this->stdout("Sending email to parntner\n");
        $order->sendEmailToPartner();
        $this->stdout("Done!\n");
    }

    public function actionTestRegisterEmail() {
        \Yii::$app->mailer->compose(
            [
                'html' => 'partnerConfirmEmailWithPassword-html.php',
                'text' => 'partnerConfirmEmailWithPassword-text.php',
            ], [
                'link' => 'https://partner.king-boo.com/confirm-email?code=bv7eyt894qc4 8tc',
                'resendCode' => 'https://partner.king-boo.com/confirm-email?code=bvfwyr4c894cyt4b89c',
                'password' => '12345',
                'email' => 'test@mail.ru',
            ]
        )
            ->setFrom(\Yii::$app->params['email.from'])
            ->setTo('mn@itdesign.ru')
            ->setSubject(\Yii::t('partner_login', 'Email confirmation for site partner.king-boo.com'))
            ->send();
    }

}
