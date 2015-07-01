<?php

use yii\db\Schema;
use yii\db\Migration;

class m150701_110546_add_admin_unread_feild_to_support_messages extends Migration
{
    public function up()
    {
        $this->addColumn('{{%support_messages}}', 'unread_admin', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%support_messages}}', 'unread_admin');
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
