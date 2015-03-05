<?php

use yii\db\Schema;
use yii\db\Migration;

class m150305_171357_create_countries_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%countries}}', [
            'id' => Schema::TYPE_PK,
            'name_ru' => Schema::TYPE_STRING . ' NOT NULL',
            'name_en' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);

        $this->batchInsert('{{%countries}}', ['name_ru', 'name_en'], [
            ['Россия', 'Russia'],
            ['Беларусь', 'Belarus'],
            ['Турция', 'Turkey'],
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%countries}}');
    }
}
