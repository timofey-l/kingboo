<?php

use yii\db\Schema;
use yii\db\Migration;

class m150908_104743_currency_change extends Migration
{
    public function up()
    {
        $this->truncateTable('{{%currencies}}');
        $this->addColumn('{{%currencies}}', 'invoice_symbol', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('{{%currencies}}', 'dec_point', Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn('{{%currencies}}', 'thousands_sep', Schema::TYPE_STRING . ' NOT NULL');
        $this->batchInsert('{{%currencies}}', ['id', 'name_ru', 'name_en', 'code', 'iso_code', 'symbol', 'invoice_symbol', 'format', 'dec_point', 'thousands_sep'], [
            [1, 'USD', 'USD', 'USD', 840, '<i class="fa fa-dollar"></i>', '<i class="fa fa-dollar"></i>', '{symbol} {value}', '.', ','],
            [2, 'Евро', 'Euro', 'EUR', 978, '<i class="fa fa-eur"></i>', '<i class="fa fa-eur"></i>', '{symbol} {value}', '.', ','],
            [3, 'Российский рубль', 'Russian Ruble', 'RUB', 643, '<i class="fa fa-rub"></i>', 'руб.', '{value} {symbol}', '.', '&nbsp;'],
        ]);
    }

    public function down()
    {
        $this->dropColumn('{{%currencies}}', 'invoice_symbol');
        $this->dropColumn('{{%currencies}}', 'dec_point');
        $this->dropColumn('{{%currencies}}', 'thousands_sep');
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
