<?php

use yii\db\Schema;
use yii\db\Migration;

class m150220_071922_foods extends Migration
{
    public function up()
    {
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$this->createTable('{{%food_type}}', [
			'id' => Schema::TYPE_PK,
			'name_ru' => Schema::TYPE_STRING . '(255) NOT NULL',
			'name_en' => Schema::TYPE_STRING . '(255) NOT NULL',
			'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
		], $tableOptions);

		$this->batchInsert('{{%food_type}}', ['name_ru', 'name_en', 'sort'],[
			['RO Без питания', 'RO Room only', 10],
			['BB Завтрак', 'BB Bed & breakfast', 20],
			['HB Полупансион', 'HB Half board', 30],
			['FB Полный пансион', 'FB Full board', 40],
			['AI Всё включено (завтрак, обед, ужин)', 'AI All inclusive', 50],
			['UAI (завтрак, поздний завтрак, обед, полдник и ужин)', 'UAI Ultra all inclusive', 60],
		]);
    }

    public function down()
    {
		$this->dropTable('{{%food_type}}');
    }
}
