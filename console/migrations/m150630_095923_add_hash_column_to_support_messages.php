<?php

use yii\db\Migration;
use yii\db\Schema;

class m150630_095923_add_hash_column_to_support_messages extends Migration
{
    public function up()
    {
        $this->addColumn('{{%support_messages}}', 'hash', Schema::TYPE_STRING);
        $this->createIndex('hash_unique', '{{%support_messages}}', ['hash'], true);
    }

    public function down()
    {
        $this->dropColumn('{{%support_messages}}', 'hash');
        $this->dropIndex('hash_unique', '{{%support_messages}}');
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
