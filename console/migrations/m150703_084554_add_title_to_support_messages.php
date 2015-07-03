<?php

use yii\db\Schema;
use yii\db\Migration;

class m150703_084554_add_title_to_support_messages extends Migration
{
    public function up()
    {
        $this->addColumn('{{%support_messages}}', 'title', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('{{%support_messages}}', 'title');
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
