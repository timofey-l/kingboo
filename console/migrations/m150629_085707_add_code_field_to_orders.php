<?php

use yii\db\Schema;
use yii\db\Migration;

class m150629_085707_add_code_field_to_orders extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'code', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'code');
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
