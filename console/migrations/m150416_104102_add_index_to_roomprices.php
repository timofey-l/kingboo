<?php

use yii\db\Schema;
use yii\db\Migration;

class m150416_104102_add_index_to_roomprices extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createIndex('uniqueAll', '{{%room_prices}}', ['date', 'room_id', 'adults', 'children', 'kids'], true);
    }

    public function down()
    {
       $this->dropIndex('uniqueAll', '{{%room_prices}}');
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
