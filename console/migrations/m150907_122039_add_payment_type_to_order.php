<?php

use common\models\Order;
use yii\db\Schema;
use yii\db\Migration;

class m150907_122039_add_payment_type_to_order extends Migration
{
    public function up()
    {
        $this->addColumn(Order::tableName(), 'yandex_payment_type', Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\'');
    }

    public function down()
    {
        $this->dropColumn(Order::tableName(), 'yandex_payment_type');
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
