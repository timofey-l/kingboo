<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_112357_pay_log_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pay_logs}}', 'code', Schema::TYPE_INTEGER );
        $this->addColumn('{{%pay_logs}}', 'notes', Schema::TYPE_STRING );
    }

    public function down()
    {
        $this->dropColumn('{{%pay_logs}}', 'code');
        $this->dropColumn('{{%pay_logs}}', 'notes');
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
