<?php

use yii\db\Schema;
use yii\db\Migration;

class m150727_120737_add_phone_and_email_to_hotel extends Migration
{
    public function up()
    {
        $this->addColumn('{{%hotel}}', 'contact_phone', Schema::TYPE_STRING . " DEFAULT ''");
        $this->addColumn('{{%hotel}}', 'contact_email', Schema::TYPE_STRING . " DEFAULT ''");

    }

    public function down()
    {
        $this->dropColumn('{{%hotel}}', 'contact_phone');
        $this->dropColumn('{{%hotel}}', 'contact_email');
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
