<?php

use yii\db\Schema;
use yii\db\Migration;

class m150711_132003_add_lang_to_partner_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'lang', Schema::TYPE_STRING . " NOT NULL DEFAULT 'ru'");
    }

    public function down()
    {
        $this->dropColumn('{{%partner_user}}', 'lang');
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
