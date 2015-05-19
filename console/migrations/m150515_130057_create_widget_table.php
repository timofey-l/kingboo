<?php

use yii\db\Schema;
use yii\db\Migration;

class m150515_130057_create_widget_table extends Migration
{
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	    }

	    $this->createTable('{{%widget}}', [
		    'id' => Schema::TYPE_PK,
		    'hotel_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'hash_code' => Schema::TYPE_STRING . ' NOT NULL',
		    'params' => Schema::TYPE_TEXT . ' NOT NULL',
		    'comment' => Schema::TYPE_STRING,
		    'compiled_js' => Schema::TYPE_TEXT,
		    'compiled_css' => Schema::TYPE_TEXT,
	    ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%widget}}');
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
