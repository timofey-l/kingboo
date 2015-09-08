<?php
namespace console\controllers;

use common\components\BookingHelper;
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
        $c = \common\models\Currency::find()->where(['code' => 'EUR'])->one();
        echo $c->convertTo(100,'EUR',5)."\n";
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
            $hotel->address = 'Camyuva Mah. 5087 Sok. No: 10/1, Kemer, Antalya/Turkey';
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


    public function actionExpensesUpdate($showDebugInfo = false)
    {
        // полчаем все активные подключенные тарифы
        $accountServices = BillingAccountServices::find()->where(['active' => 1])->all();

        $n = 0;
        if ($accountServices) $n = count($accountServices);

        if ($showDebugInfo) $this->stdout("Найдено {$n} активных тарифов.\n\n", Console::BOLD);

        foreach($accountServices as $accountService) {
            if ($showDebugInfo) $this->stdout("Тарифный план id:{$accountService->id}\n");
            /** @var BillingAccountServices $accountService */
            /** @var BillingAccount $account */
            $account = $accountService->account;
            /** @var BillingService $service */
            $service = $accountService->service;

            if ($showDebugInfo) $this->stdout("Услуга: {$service->name_ru} ({$service->monthly_cost} {$service->currency->code} / мес.)\n");
            if ($showDebugInfo) $this->stdout("Клиент: {$account->partner->email} (id: {$account->partner->id})\n");

            /** @var BillingExpense $lastExpense */
            $lastExpense = BillingExpense::find()->where(['account_id' => $account->id, 'service_id' => $service->id])->orderBy(['date' => SORT_DESC])->one();

            $allowExpense = true;
            if ($lastExpense === null) {
                if ($showDebugInfo) $this->stdout("Последнего списания не найдено.\n");
            }
            // если последнее списание было менее чем 23 часа назад
            // тогда запрет на списание
            if (!is_null($lastExpense)) {
                $dateLastExpense = new DateTime($lastExpense->date);
                if ($showDebugInfo) $this->stdout("Дата последнего списания: {$lastExpense->date}\n");
                $h = (new DateTime('now'))->diff($dateLastExpense)->h + (new DateTime('now'))->diff($dateLastExpense)->d * 24;
                if ($showDebugInfo) $this->stdout("Разница в часах: {$h}\n");
                if ($h < 23) {
                    $allowExpense = false;
                }
            }

            // если тестовый период еще не закончен - запрет на списание
            if ($showDebugInfo) $this->stdout("Дата истечения тестового периода: {$account->partner->demo_expire}\n");
            if ((new DateTime($account->partner->demo_expire))->diff(new DateTime())->invert) {
                $allowExpense = false;
            }

            // проверяем баланс и если отрицательный - запрет на списывание
            $account->updateBalance();
            $balance = $account->balance . ' ' . $account->currency->code;
            if ($showDebugInfo) $this->stdout("Баланс на аккаунте: {$balance}\n");
            if ($account->balance <= 0) {
                $allowExpense = false;
            }

            // если разрешено списывать - делаем это
            if ($allowExpense) {
//            if (true) {
                if ($showDebugInfo) $this->stdout("Списание средств возможно.\n");
                // определим сумму для списания (месячная сумма * 12 / 365)
                $sum = round($service->monthly_cost * 12 / 365, 2);

                $newExpense = new BillingExpense;

                $newExpense->account_id = $account->id;
                $newExpense->sum = $sum;
                $newExpense->currency_id = $service->currency_id;
                $newExpense->date = date(DateTime::ISO8601);
                $newExpense->sum_currency_id = $service->currency_id;
                $newExpense->service_id = $service->id;
                $newExpense->comment = "Ежедневное списание средств по тарифу \"{$service->name_ru}\" (id: {$accountService->id})";
                if ($newExpense->save()) {
                    $sum = $newExpense->sum . ' ' . $service->currency->code;
                    if ($showDebugInfo) $this->stdout("Успешно списано {$sum}. ID записи списания: {$newExpense->id}\n");
                } else {
                    $errors = var_export($newExpense->errors, true);
                    if ($showDebugInfo) $this->stderr("При списании средств произошла ошибка. Ошибки модели:\n");
                    if ($showDebugInfo) $this->stderr($errors);
                }

            } else {
                if ($showDebugInfo) $this->stdout("Списание средств не возможно.\n");
            }

            if ($showDebugInfo) $this->stdout("\n");
        }
    }

    public function actionTest($id = 1) {
        /** @var BillingAccount $account */
        $account = BillingAccount::findOne($id);
        $account->updateBalance();
        var_dump($account->balance);
	}

}