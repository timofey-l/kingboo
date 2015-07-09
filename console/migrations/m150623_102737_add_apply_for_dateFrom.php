<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_102737_add_apply_for_dateFrom extends Migration
{
    public function up()
    {
        $this->addColumn('{{%price_rules}}', 'applyForCheckIn', Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT 0");
    }

    public function down()
    {
        $this->dropColumn('{{%price_rules}}', 'applyForCheckin');
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
