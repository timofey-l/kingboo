<?php

use yii\db\Schema;
use yii\db\Migration;

class m150515_110227_room_availability extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%room_availability}}', [
            'id' => Schema::TYPE_PK,
            'date' => Schema::TYPE_DATE . ' NOT NULL',
            'room_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'count' => Schema::TYPE_INTEGER . ' NOT NULL',
            'availability' => Schema::TYPE_SMALLINT . ' NOT NULL',
        ], $tableOptions);


    }

    public function down()
    {
        $this->dropTable('{{%room_availability}}');
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
