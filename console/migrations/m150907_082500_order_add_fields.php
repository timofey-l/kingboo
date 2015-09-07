<?php

use yii\db\Schema;
use yii\db\Migration;

class m150907_082500_order_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'sum_currency_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0' );
        $this->addColumn('{{%order}}', 'pay_sum_currency_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0' );
        $this->addColumn('{{%order}}', 'payment_system_sum', Schema::TYPE_FLOAT );
        $this->addColumn('{{%order}}', 'payment_system_sum_currency_id', Schema::TYPE_INTEGER );
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'sum_currency_id');
        $this->dropColumn('{{%order}}', 'pay_sum_currency_id');
        $this->dropColumn('{{%order}}', 'payment_system_sum');
        $this->dropColumn('{{%order}}', 'payment_system_sum_currency_id');
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
