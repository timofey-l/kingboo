<?php

use yii\db\Schema;
use yii\db\Migration;

class m150819_110300_partner_user_add_field extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{%partner_user}}', 'system_info', Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('{{%partner_user}}', 'system_info');
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
