<?php

use yii\db\Schema;
use yii\db\Migration;

class m150521_064519_partial_pay_add extends Migration
{
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	    }

	    $this->addColumn('{{%hotel}}', 'allow_partial_pay', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE');
	    $this->addColumn('{{%hotel}}', 'partial_pay_percent', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 100');
    }

    public function down()
    {
        $this->dropColumn('{{%hotel}}', 'allow_partial_pay');
	    $this->dropColumn('{{%hotel}}', 'partial_pay_percent');
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
