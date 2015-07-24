<?php

use yii\db\Schema;
use yii\db\Migration;

class m150702_141004_add_currency_format extends Migration
{
    public function up()
    {
        $this->addColumn('{{%currencies}}', 'format', Schema::TYPE_STRING);

        $this->truncateTable('{{%currencies}}');
        $this->batchInsert('{{%currencies}}', ['name_ru', 'name_en', 'code', 'symbol', 'format'], [
            ['USD', 'USD', 'USD', '$', '{symbol}{value}'],
            ['Евро', 'Euro', 'EUR', '&euro;', '{symbol}{value}'],
            ['Российский рубль', 'Russian Ruble', 'RUB', '&#8381;', '{value}{symbol}'],
        ]);

    }

    public function down()
    {
        $this->dropColumn('{{%currencies}}', 'format');

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
