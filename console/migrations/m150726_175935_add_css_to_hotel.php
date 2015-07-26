<?php

use yii\db\Migration;
use yii\db\Schema;

class m150726_175935_add_css_to_hotel extends Migration
{
    public function up()
    {
        $this->addColumn('{{%hotel}}', 'less', Schema::TYPE_TEXT);
        $this->addColumn('{{%hotel}}', 'css', Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('{{%hotel}}', 'css');
        $this->dropColumn('{{%hotel}}', 'less');
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
