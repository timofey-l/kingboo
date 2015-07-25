<?php

use yii\db\Schema;
use yii\db\Migration;

class m150725_164431_add_amount_to_rooms extends Migration
{
    public function up()
    {
        $this->addColumn('{{%room}}', 'amount', Schema::TYPE_INTEGER . ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%room}}', 'amount');
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
