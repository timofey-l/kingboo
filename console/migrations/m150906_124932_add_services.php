<?php

use common\models\BillingService;
use common\models\Currency;
use yii\db\Schema;
use yii\db\Migration;

class m150906_124932_add_services extends Migration
{
    public function up()
    {
        $currency_rub = Currency::findOne(['code' => "RUB"]);

        $this->batchInsert(BillingService::tableName(), ['name_ru', 'description_ru', 'name_en', 'description_en','archived', 'default', 'monthly', 'unique', 'currency_id', 'monthly_cost'],[
            [
                'Базовый', 'Основной тарифный план - 999 руб/мес',
                'Base', 'The main tariff plan - 999 RUR / month',
                false, // archived
                true, // default
                true, // monthly
                true, // unique
                $currency_rub->id,
                999,
            ],
            [
                'Собственный домен', 'Доступ к сайту отеля по своему домену',
                'Own domain', 'Access to the site of the hotel on your domain',
                false, // archived
                false, // default
                true, // monthly
                true, // unique
                $currency_rub->id,
                199,
            ],

        ]);
    }

    public function down()
    {
        echo "m150906_124932_add_services cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
