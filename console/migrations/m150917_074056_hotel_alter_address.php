<?php

use yii\db\Schema;
use yii\db\Migration;

class m150917_074056_hotel_alter_address extends Migration
{
    public function up()
    {
        $this->renameColumn('{{%hotel}}', 'address', 'address_ru');
        $this->addColumn('{{%hotel}}', 'address_en', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->renameColumn('{{%hotel}}', 'address_ru', 'address');
        $this->dropColumn('{{%hotel}}', 'address_en');
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
