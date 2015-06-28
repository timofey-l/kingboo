<?php

use yii\db\Schema;
use yii\db\Migration;

class m150628_105250_add_partner_field_to_pricerules extends Migration
{
    public function up()
    {
        $this->addColumn('{{%price_rules}}', 'partner_id', Schema::TYPE_INTEGER . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%price_rules}}', 'partner_id');
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
