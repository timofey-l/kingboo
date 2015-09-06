<?php

use yii\db\Schema;
use yii\db\Migration;

class m150901_135921_exchange_rates extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%exchange_rates}}', [
            'id' => Schema::TYPE_PK,
            'date' => Schema::TYPE_DATETIME,
            'rates' => Schema::TYPE_TEXT,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%exchange_rates}}');
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
