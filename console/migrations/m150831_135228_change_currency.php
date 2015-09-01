<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_135228_change_currency extends Migration
{
    public function up()
    {
        $this->truncateTable('{{%currencies}}');
        $this->batchInsert('{{%currencies}}', ['id', 'name_ru', 'name_en', 'code', 'symbol', 'format'], [
            [1, 'USD', 'USD', 'USD', '<i class="fa fa-dollar"></i>', '{symbol} {value}'],
            [2, 'Евро', 'Euro', 'EUR', '<i class="fa fa-eur"></i>', '{symbol} {value}'],
            [3, 'Российский рубль', 'Russian Ruble', 'RUB', '<i class="fa fa-rub"></i>', '{value} {symbol}'],
        ]);

    }

    public function down()
    {
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
