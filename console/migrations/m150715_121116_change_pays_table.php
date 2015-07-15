<?php

use yii\db\Schema;
use yii\db\Migration;

class m150715_121116_change_pays_table extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%pays}}', 'order_number', Schema::TYPE_STRING);
        $this->alterColumn('{{%pays}}', 'customerNumber', Schema::TYPE_STRING);
        $this->alterColumn('{{%pays}}', 'paymentDatetime', Schema::TYPE_STRING);
    }

    public function down()
    {
        echo "m150715_121116_change_pays_table cannot be reverted.\n";

        return false;
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
