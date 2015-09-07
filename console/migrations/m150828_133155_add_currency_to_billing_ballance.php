<?php

use yii\db\Schema;
use yii\db\Migration;

class m150828_133155_add_currency_to_billing_ballance extends Migration
{
    public function up()
    {
        $this->addColumn('{{%billing_account}}', 'currency_id', Schema::TYPE_INTEGER . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%billing_account}}', 'currency_id');
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
