<?php

use yii\db\Schema;
use yii\db\Migration;

class m150305_180729_create_currencies_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%currencies}}', [
            'id' => Schema::TYPE_PK,
            'name_ru' => Schema::TYPE_STRING . ' NOT NULL',
            'name_en' => Schema::TYPE_STRING . ' NOT NULL',
            'code' => Schema::TYPE_STRING . '(3) NOT NULL',
            'symbol' => Schema::TYPE_STRING . '(50) NOT NULL',
        ], $tableOptions);

        $this->batchInsert('{{%currencies}}',['name_ru', 'name_en', 'code', 'symbol'], [
            ['Российский рубль', 'Russian Ruble', 'RUB', '&#8381;']
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%currncies}}');
    }
}
