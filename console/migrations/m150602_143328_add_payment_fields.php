<?php

use yii\db\Schema;
use yii\db\Migration;

class m150602_143328_add_payment_fields extends Migration
{
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	    }

	    $this->addColumn('{{%partner_user}}', 'shopId', Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\'');
	    $this->addColumn('{{%partner_user}}', 'shopPassword', Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\'');
	    $this->addColumn('{{%partner_user}}', 'scid', Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\'');

	    $this->createIndex('order_number', '{{%order}}', ['number'], true);
    }

    public function down()
    {
        $this->dropIndex('order_number', '{{%order}}');

	    $this->dropColumn('{{%order}}', 'scid');
	    $this->dropColumn('{{%order}}', 'shopPassword');
	    $this->dropColumn('{{%order}}', 'shopId');
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
