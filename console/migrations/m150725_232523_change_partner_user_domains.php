<?php

use yii\db\Schema;
use yii\db\Migration;

class m150725_232523_change_partner_user_domains extends Migration
{
    public function up()
    {
        $this->addColumn('{{%hotel}}', 'domain', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('{{%hotel}}', 'domain');
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
