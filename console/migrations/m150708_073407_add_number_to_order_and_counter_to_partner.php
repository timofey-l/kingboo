<?php

use yii\db\Schema;
use yii\db\Migration;

class m150708_073407_add_number_to_order_and_counter_to_partner extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'partner_number', Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\'');
        $this->addColumn('{{%partner_user}}', 'counter', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'partner_number');
        $this->dropColumn('{{%partner_user}}', 'counter');
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
