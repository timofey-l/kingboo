<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_135228_change_currency extends Migration
{
    public function up()
    {
        $this->addColumn('{{%currencies}}', 'font_awesome', Schema::TYPE_STRING);

        $this->truncateTable('{{%currencies}}');
        $this->batchInsert('{{%currencies}}', ['name_ru', 'name_en', 'code', 'symbol', 'format', 'font_awesome'], [
            ['USD', 'USD', 'USD', '<i class="fa fa-fw fa-dollar"></i>', '{symbol} {value}', 'fa-dollar'],
            ['Евро', 'Euro', 'EUR', '<i class="fa fa-fw fa-eur"></i>', '{symbol} {value}', ' fa-eur'],
            ['Российский рубль', 'Russian Ruble', 'RUB', '<i class="fa fa-fw fa-rub"></i>', '{value} {symbol}', 'fa-rub'],
        ]);

    }

    public function down()
    {
        $this->dropColumn('{{%currencies}}', 'font_awesome');

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
