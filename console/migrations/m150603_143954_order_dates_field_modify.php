<?php

use yii\db\Schema;
use yii\db\Migration;

class m150603_143954_order_dates_field_modify extends Migration
{
    public function up()
    {
	    $this->alterColumn('{{%order}}', 'created_at', Schema::TYPE_DATETIME . ' NOT NULL');
	    $this->alterColumn('{{%order}}', 'updated_at', Schema::TYPE_DATETIME . ' NOT NULL');
    }

    public function down()
    {

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
