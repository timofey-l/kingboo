<?php

use yii\db\Schema;
use yii\db\Migration;

class m150605_150141_add_payment_url_to_order extends Migration
{
    public function up()
    {
		$this->addColumn('{{%order}}', 'payment_url', Schema::TYPE_STRING . '(64) NOT NULL');
    }

    public function down()
    {
	    $this->dropColumn('{{%order}}', 'payment_url');
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
