<?php

use yii\db\Schema;
use yii\db\Migration;

class m150417_131838_hotel_image extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%hotel_image}}', [
            'id' => Schema::TYPE_PK,
            'hotel_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'image' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%hotel_image}}');
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
