<?php

use yii\db\Schema;
use yii\db\Migration;

class m151101_124334_add_auth_token_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%direct_login_tokens}}', [
            'id' => Schema::TYPE_PK,
            'token' => Schema::TYPE_STRING . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'expire_date' => Schema::TYPE_DATETIME . ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%direct_login_tokens}}');
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
