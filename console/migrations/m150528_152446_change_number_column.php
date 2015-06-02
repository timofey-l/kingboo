<?php

use yii\db\Schema;
use yii\db\Migration;

class m150528_152446_change_number_column extends Migration
{
    public function up()
    {
	    $this->alterColumn('{{%order}}', 'number', Schema::TYPE_STRING . '(32) NOT NULL');
    }

    public function down()
    {

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
