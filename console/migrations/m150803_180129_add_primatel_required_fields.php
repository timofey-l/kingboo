<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_180129_add_primatel_required_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'private_person', Schema::TYPE_BOOLEAN . ' DEFAULT FALSE');
        $this->addColumn('{{%partner_user}}', 'company_name', Schema::TYPE_STRING . ' DEFAULT \'\'');
        $this->addColumn('{{%partner_user}}', 'phone', Schema::TYPE_STRING . ' DEFAULT \'\'');

        $this->addColumn('{{%partner_user}}', 'demo_expire', Schema::TYPE_DATE . ' DEFAULT NOW()');
    }

    public function down()
    {
        echo "m150803_180129_add_primatel_required_fields cannot be reverted.\n";

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
