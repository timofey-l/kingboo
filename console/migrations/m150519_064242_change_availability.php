<?php

use yii\db\Schema;
use yii\db\Migration;

class m150519_064242_change_availability extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->renameColumn('{{%room_availability}}', 'availability', 'stop_sale');

    }

    public function down()
    {
        $this->renameColumn('{{%room_availability}}', 'stop_sale', 'availability');
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
