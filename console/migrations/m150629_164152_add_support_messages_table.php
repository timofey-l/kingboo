<?php

use yii\db\Schema;
use yii\db\Migration;

class m150629_164152_add_support_messages_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%support_messages}}', [
            'id' => Schema::TYPE_PK,
            'unread' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_DATETIME . ' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME . ' NOT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'author' => Schema::TYPE_INTEGER,
            'parent_id' => Schema::TYPE_INTEGER,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%support_messages}}');
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
