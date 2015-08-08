<?php

use yii\db\Schema;
use yii\db\Migration;

class m150805_191946_add_fields_to_partnerUser extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'prima_login', Schema::TYPE_STRING . ' DEFAULT \'\'');
    }

    public function down()
    {
        echo "m150805_191946_add_fields_to_partnerUser cannot be reverted.\n";

        return false;
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
