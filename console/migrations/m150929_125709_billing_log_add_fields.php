<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_125709_billing_log_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%billing_logs}}', 'code', Schema::TYPE_INTEGER );
        $this->addColumn('{{%billing_logs}}', 'notes', Schema::TYPE_STRING );
    }

    public function down()
    {
        $this->dropColumn('{{%billing_logs}}', 'code');
        $this->dropColumn('{{%billing_logs}}', 'notes');
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
