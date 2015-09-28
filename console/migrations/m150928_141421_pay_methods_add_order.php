<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_141421_pay_methods_add_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pay_methods}}', 'order', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1');
    }

    public function down()
    {
        $this->dropColumn('{{%pay_methods}}', 'order');
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
