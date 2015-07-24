<?php

use yii\db\Schema;
use yii\db\Migration;

class m150401_182613_rooms_and_roomPrices extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%room}}', [
            'id' => Schema::TYPE_PK,
            'hotel_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title_ru' => Schema::TYPE_STRING . '(255) NOT NULL',
            'title_en' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description_ru' => Schema::TYPE_TEXT . ' NOT NULL',
            'description_en' => Schema::TYPE_TEXT . ' NOT NULL',
            'adults' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'children' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'total' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'active' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
        ], $tableOptions);

        $this->createTable('{{%room_price}}', [
            'id' => Schema::TYPE_PK,
            'date' => Schema::TYPE_DATE . ' NOT NULL',
            'price' => Schema::TYPE_DECIMAL . '(12,2) NOT NULL',
            'price_currency' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%room}}');
        $this->dropTable('{{%room_price}}');
    }
}
