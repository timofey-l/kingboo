<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_150929_partner_user_add_yandexdemo extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'yandex_demo', Schema::TYPE_BOOLEAN . ' DEFAULT TRUE');
    }

    public function down()
    {
        $this->dropColumn('{{%partner_user}}', 'yandex_demo');
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
