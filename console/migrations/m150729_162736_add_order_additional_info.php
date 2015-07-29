<?php

use yii\db\Schema;
use yii\db\Migration;

class m150729_162736_add_order_additional_info extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'additional_info', Schema::TYPE_TEXT . ' DEFAULT \'\'');
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'additional_info');
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
