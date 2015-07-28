<?php

use yii\db\Schema;
use yii\db\Migration;

class m150728_083852_add_checkin_fullpay_to_orders extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'checkin_fullpay', Schema::TYPE_SMALLINT . ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'checkin_fullpay');
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
