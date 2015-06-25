<?php

use yii\db\Schema;
use yii\db\Migration;

class m150622_210729_add_code_to_pricerules extends Migration
{
    public function up()
    {
        $this->addColumn('{{%price_rules}}', 'code', Schema::TYPE_STRING );
    }

    public function down()
    {
        $this->dropColumn('{{%price_rules}}', 'code');
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
