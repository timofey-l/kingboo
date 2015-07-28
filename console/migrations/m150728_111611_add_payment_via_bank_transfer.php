<?php

use yii\db\Schema;
use yii\db\Migration;

class m150728_111611_add_payment_via_bank_transfer extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'allow_payment_via_bank_transfer', Schema::TYPE_SMALLINT . ' DEFAULT 0');
        $this->addColumn('{{%order}}', 'payment_via_bank_transfer', Schema::TYPE_SMALLINT . ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'payment_via_bank_transfer');
        $this->dropColumn('{{%partner_user}}', 'allow_payment_via_bank_transfer');
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
