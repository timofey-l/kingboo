<?php

use yii\db\Schema;
use yii\db\Migration;

class m150715_112626_add_shopId_to_pay extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pays}}', 'shopId', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('{{%pays}}', 'shopId');
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
