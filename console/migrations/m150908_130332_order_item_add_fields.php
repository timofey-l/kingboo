<?php

use yii\db\Schema;
use yii\db\Migration;

class m150908_130332_order_item_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order_item}}', 'sum_currency_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0' );
        $this->addColumn('{{%order_item}}', 'pay_sum', Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0' );
        $this->addColumn('{{%order_item}}', 'pay_sum_currency_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0' );
        $this->addColumn('{{%order_item}}', 'payment_system_sum', Schema::TYPE_FLOAT );
        $this->addColumn('{{%order_item}}', 'payment_system_sum_currency_id', Schema::TYPE_INTEGER );
    }

    public function down()
    {
        $this->dropColumn('{{%order_item}}', 'sum_currency_id');
        $this->dropColumn('{{%order_item}}', 'pay_sum');
        $this->dropColumn('{{%order_item}}', 'pay_sum_currency_id');
        $this->dropColumn('{{%order_item}}', 'payment_system_sum');
        $this->dropColumn('{{%order_item}}', 'payment_system_sum_currency_id');
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
