<?php

use yii\db\Schema;
use yii\db\Migration;

class m150917_105441_hotel_langs extends Migration
{
    public function up()
    {
        $this->addColumn('{{%hotel}}', 'ru', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE');
        $this->addColumn('{{%hotel}}', 'en', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE');
    }

    public function down()
    {
        $this->dropColumn('{{%hotel}}', 'ru');
        $this->dropColumn('{{%hotel}}', 'en');
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
