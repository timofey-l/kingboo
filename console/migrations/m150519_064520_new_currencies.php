<?php

use yii\db\Schema;
use yii\db\Migration;

class m150519_064520_new_currencies extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->batchInsert('{{%currencies}}',['name_ru', 'name_en', 'code', 'symbol'], [
            ['USD', 'USD', 'USD', '$']
        ]);
        $this->batchInsert('{{%currencies}}',['name_ru', 'name_en', 'code', 'symbol'], [
            ['Евро', 'Euro', 'EUR', '&euro;']
        ]);

    }

    public function down()
    {
        $this->delete('{{%currencies}}', ['code' => 'USD']);
        $this->delete('{{%currencies}}', ['code' => 'EUR']);
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
