<?php

use yii\db\Schema;
use yii\db\Migration;

class m150723_212027_add_checked_to_partner_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'checked', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%partner_user}}', 'checked');
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
