<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_215821_billing_income_add_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%billing_income}}', 'pay_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0' );
    }

    public function down()
    {
        $this->dropColumn('{{%billing_income}}', 'pay_id');
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
