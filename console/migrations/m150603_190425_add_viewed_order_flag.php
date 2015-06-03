<?php

use yii\db\Schema;
use yii\db\Migration;

class m150603_190425_add_viewed_order_flag extends Migration
{
    public function up()
    {
		$this->addColumn("{{%order}}", 'viewed', Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT 0");
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
