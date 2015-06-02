<?php

use yii\db\Schema;
use yii\db\Migration;

class m150601_153327_add_partner_pay_types extends Migration
{
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	    }

	    $this->createTable('{{%partner_pay_types}}', [
		    'partner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'pay_type' => Schema::TYPE_INTEGER . ' NOT NULL',
	    ], $tableOptions);

	    $this->addPrimaryKey('PK_partner_pay_types', '{{%partner_pay_types}}', ['partner_id', 'pay_type']);
    }

    public function down()
    {
	    $this->dropPrimaryKey('PK_partner_pay_types', '{{%partner_pay_types}}');
        $this->dropTable('{{%partner_pay_types}}');
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
