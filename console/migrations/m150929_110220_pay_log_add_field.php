<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_110220_pay_log_add_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pay_logs}}', 'type', Schema::TYPE_STRING );
    }

    public function down()
    {
        $this->dropColumn('{{%pay_logs}}', 'type');
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
