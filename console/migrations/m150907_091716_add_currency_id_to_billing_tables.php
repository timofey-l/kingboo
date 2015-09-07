<?php

use yii\db\Schema;
use yii\db\Migration;

class m150907_091716_add_currency_id_to_billing_tables extends Migration
{
    public function up()
    {
        $this->addColumn('{{%billing_expenses}}', 'sum_currency_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%billing_expenses}}', 'sum_currency_id');
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
