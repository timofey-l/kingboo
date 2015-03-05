<?php

use yii\db\Schema;
use yii\db\Migration;

class m150217_164745_add_hotels_table extends Migration
{
    public function up()
    {
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
        $this->createTable('{{%hotel}}', [
            'id' => Schema::TYPE_PK,
            'partner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'address' => Schema::TYPE_STRING . '(255) NOT NULL',
            'lng' => Schema::TYPE_FLOAT . ' NOT NULL',
            'lat' => Schema::TYPE_FLOAT . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'category' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'timezone' => Schema::TYPE_STRING,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%hotel}}');
    }
}
