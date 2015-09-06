<?php

use yii\db\Schema;
use yii\db\Migration;

class m150902_120105_partner_user_add_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'currency_exchange_percent', Schema::TYPE_DECIMAL . '(12,2) NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%partner_user}}', 'currency_exchange_percent');
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
