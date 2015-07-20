<?php

use yii\db\Schema;
use yii\db\Migration;

class m150720_163745_add_iso_code_to_currency extends Migration
{
    public function up()
    {
        $this->addColumn('{{%currencies}}', 'iso_code', Schema::TYPE_STRING . ' NOT NULL');

        $this->update('{{%currencies}}', ['iso_code' => '840'], ['code'=>'USD']);
        $this->update('{{%currencies}}', ['iso_code' => '643'], ['code'=>'RUB']);
        $this->update('{{%currencies}}', ['iso_code' => '978'], ['code'=>'EUR']);
    }

    public function down()
    {
        $this->dropColumn('{{%currencies}}', 'iso_code');
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
