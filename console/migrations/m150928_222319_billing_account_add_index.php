<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_222319_billing_account_add_index extends Migration
{
    public function up()
    {
        $this->createIndex('partner', '{{%billing_account}}', ['partner_id'], true);     }

    public function down()
    {
        $this->dropIndex('partner', '{{%billing_account}}');     }

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
