<?php

use yii\db\Schema;
use yii\db\Migration;

class m150424_094148_rel_hotel_facility extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%rel_hotel_facility}}', [
            'hotel_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'facility_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
        $this->addPrimaryKey('PK', '{{%rel_hotel_facility}}', ['hotel_id', 'facility_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%rel_hotel_facility}}');
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
