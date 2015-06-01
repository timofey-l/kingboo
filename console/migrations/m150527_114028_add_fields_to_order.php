<?php

use yii\db\Schema;
use yii\db\Migration;

class m150527_114028_add_fields_to_order extends Migration
{
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	    }

	    $this->addColumn('{{%order}}', 'hotel_id', Schema::TYPE_INTEGER . ' NOT NULL');
	    $this->addColumn('{{%order}}', 'lang', Schema::TYPE_STRING . '(3) NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'hotel_id');
        $this->dropColumn('{{%order}}', 'lang');
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
