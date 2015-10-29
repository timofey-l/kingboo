<?php

use yii\db\Schema;
use yii\db\Migration;

class m151029_134347_add_calls_stats_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%calls_stats}}', [
            'id' => Schema::TYPE_PK,
            'date' => Schema::TYPE_DATETIME,
            'company_name' => Schema::TYPE_STRING . " NOT NULL",
            'email' => Schema::TYPE_STRING . " NOT NULL",
            'phone' => Schema::TYPE_STRING . " NOT NULL",
            'contact_person' => Schema::TYPE_STRING,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%calls_stats}}');
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
