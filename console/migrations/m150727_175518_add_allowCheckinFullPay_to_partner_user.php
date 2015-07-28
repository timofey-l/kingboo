<?php

use yii\db\Schema;
use yii\db\Migration;

class m150727_175518_add_allowCheckinFullPay_to_partner_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'allow_checkin_fullpay', Schema::TYPE_SMALLINT . ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%partner_user}}', 'allow_checkin_fullpay');
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
