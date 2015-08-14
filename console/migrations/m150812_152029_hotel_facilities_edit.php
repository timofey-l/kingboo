<?php

use yii\db\Schema;
use yii\db\Migration;

class m150812_152029_hotel_facilities_edit extends Migration
{
    public function up()
    {
        $this->update('{{%hotel_facilities}}', ['name_ru' => 'теннис'], ['name_ru' => 'тенис']);
        $this->update('{{%hotel_facilities}}', ['name_ru' => 'настольный теннис'], ['name_ru' => 'настольный тенис']);
        $this->update('{{%hotel_facilities}}', ['name_ru' => 'ресепшн'], ['name_ru' => 'ресепшин']);
        $this->update('{{%hotel_facilities}}', ['name_ru' => 'каное'], ['name_ru' => 'каноэ']);
        $this->delete('{{%hotel_facilities}}', ['name_ru' => 'апартмент отель']);
        $this->delete('{{%hotel_facilities}}', ['name_ru' => 'пляж с галькой']);
    }

    public function down()
    {
        echo "m150812_152029_hotel_facilities_edit cannot be reverted.\n";

        return false;
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
