<?php

use yii\db\Schema;
use yii\db\Migration;

class m150716_140231_add_pay_methods_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%pay_methods}}', [
            'id' => Schema::TYPE_PK,
            'title_ru' => Schema::TYPE_STRING . ' NOT NULL',
            'title_en' => Schema::TYPE_STRING . ' NOT NULL',
            'yandex_code' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);

        $this->batchInsert('{{%pay_methods}}', ['yandex_code', 'title_ru', 'title_en'], [
            ['PC', 'Оплата из кошелька в Яндекс.Деньгах', 'Payment from Yandex.Money purse',],
            ['AC', 'Оплата с произвольной банковской карты', 'Payment by any bank card',],
            ['MC', 'Платеж со счета мобильного телефона', 'Payment from the mobile phone account',],
            ['GP', 'Оплата наличными через кассы и терминалы', ' Cash payments via cash offices and terminals',],
            ['WM', 'Оплата из кошелька в системе WebMoney', 'Payment from  WebMoney system purse',],
            ['SB', 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн', 'Sberbank payments: payment via SMS or Sberbank Online',],
            ['MP', 'Оплата через мобильный терминал (mPOS)', 'Payment via mobile terminal (mPOS)',],
            ['AB', 'Оплата через Альфа-Клик', 'Payment via Alfa-Click',],
            ['MA', 'Оплата через MasterPass', 'Payment via MasterPass',],
            ['PB', 'Оплата через Промсвязьбанк', 'Payment via Promsvyazbank',],
        ]);

        $this->createTable('{{%partner_payMethods}}', [
            'partner_id' => Schema::TYPE_INTEGER,
            'pay_method_id' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->dropTable('{{%partner_pay_types}}');
    }

    public function down()
    {
        $this->dropTable('{{%partner_payMethods}}');
        $this->dropTable('{{%pay_methods}}');
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
