<?php

use yii\db\Schema;
use yii\db\Migration;

class m150602_062032_create_pays_table extends Migration
{
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	    }

	    $this->createTable('{{%pays}}', [
		    'id' => Schema::TYPE_PK,

		    'checked' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
		    'payed' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',

		    'order_number' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'invoiceId' => Schema::TYPE_BIGINT . ' NOT NULL',
		    'customerNumber' => Schema::TYPE_BIGINT . ' NOT NULL',
		    'orderCreatedDatetime' => Schema::TYPE_DATETIME . ' NOT NULL',
			'paymentDatetime' => Schema::TYPE_DATETIME . ' NOT NULL',

		    'orderSumAmount' => Schema::TYPE_DOUBLE . ' NOT NULL',
		    'orderSumCurrencyPaycash' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'orderSumBankPaycash' => Schema::TYPE_INTEGER . ' NOT NULL',

		    'shopSumAmount' => Schema::TYPE_DOUBLE . ' NOT NULL',
		    'shopSumCurrencyPaycash' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'shopSumBankPaycash' => Schema::TYPE_INTEGER . ' NOT NULL',

		    'paymentPayerCode' => Schema::TYPE_STRING . ' NOT NULL',
	        'paymentType' => Schema::TYPE_INTEGER . ' NOT NULL',

		    'postParams' => Schema::TYPE_TEXT,
	    ], $tableOptions);

		$this->createTable('{{%pay_logs}}', [
			'id' => Schema::TYPE_PK,
			'timestamp' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
			'postParams' => Schema::TYPE_TEXT,
			'serverParams' => Schema::TYPE_TEXT,
		], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%pays}}');
	    $this->dropTable('{{%pay_logs}}');
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
