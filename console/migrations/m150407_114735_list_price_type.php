<?php

use yii\db\Schema;
use yii\db\Migration;

class m150407_114735_list_price_type extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%list_price_type}}', [
            'id' => Schema::TYPE_PK,
            'name_ru' => Schema::TYPE_STRING . '(255) NOT NULL',
            'name_en' => Schema::TYPE_STRING . '(255) NOT NULL',
            'order' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
        ], $tableOptions);

        $this->batchInsert('{{%list_price_type}}', ['name_ru', 'name_en', 'order'],[
            ['Фиксированная цена', 'Fixed price', 10],
            ['Цена зависит от количества гостей', 'The price depends on the number of guests', 20],
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%list_price_type}}');
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
