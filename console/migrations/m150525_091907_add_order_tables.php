<?php

use yii\db\Schema;
use yii\db\Migration;

class m150525_091907_add_order_tables extends Migration
{
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	    }

	    $this->createTable('{{%order}}', [
		    'id' => Schema::TYPE_PK,
		    'created_at' => Schema::TYPE_DATE,
		    'updated_at' => Schema::TYPE_DATE,
		    'number' => Schema::TYPE_STRING . '(15) NOT NULL',
		    'status' => Schema::TYPE_INTEGER . ' NOT NULL',

		    'contact_email' => Schema::TYPE_STRING . ' NOT NULL',
		    'contact_phone' => Schema::TYPE_STRING . ' NOT NULL',
		    'contact_name' => Schema::TYPE_STRING . ' NOT NULL',
		    'contact_surname' => Schema::TYPE_STRING . ' NOT NULL',
	        'contact_address' => Schema::TYPE_INTEGER . ' NOT NULL',

		    'dateFrom' => Schema::TYPE_DATE . ' NOT NULL',
		    'dateTo' => Schema::TYPE_DATE . ' NOT NULL',
		    'sum' => Schema::TYPE_FLOAT . ' NOT NULL',
		    'partial_pay' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
		    'partial_pay_percent' => Schema::TYPE_BOOLEAN . ' NOT NULL',
		    'pay_sum' => Schema::TYPE_FLOAT . ' NOT NULL',
	    ], $tableOptions);

	    $this->createTable('{{%order_item}}', [
		    'id' => Schema::TYPE_PK,
		    'room_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'order_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'adults' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'children' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'kids' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'sum' => Schema::TYPE_FLOAT . ' NOT NULL',
		    'guest_name' => Schema::TYPE_STRING . '(255) NOT NULL',
		    'guest_surname' => Schema::TYPE_STRING . '(255) NOT NULL',
		    'guest_address' => Schema::TYPE_INTEGER . ' NOT NULL',
	    ], $tableOptions);
    }

    public function down()
    {
	    $this->dropTable('{{%order}}');
	    $this->dropTable('{{%order_item}}');
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
