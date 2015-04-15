<?php

use yii\db\Schema;
use yii\db\Migration;

class m150409_182917_add_field_to_room extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{%room}}', 'price_type', Schema::TYPE_SMALLINT . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%room}}', 'price_type');
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
