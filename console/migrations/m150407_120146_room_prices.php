<?php

use yii\db\Schema;
use yii\db\Migration;

class m150407_120146_room_prices extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->dropTable('{{%room_price}}');
        
        $this->createTable('{{%room_prices}}', [
            'id' => Schema::TYPE_PK,
            'date' => Schema::TYPE_DATE . ' NOT NULL',
            'room_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'adults' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'children' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'kids' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'price' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL DEFAULT 0',
            'price_currency' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);


    }

    public function down()
    {
        $this->dropTable('{{%room_prices}}');
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
