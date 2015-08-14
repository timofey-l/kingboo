<?php

use yii\db\Schema;
use yii\db\Migration;

class m150813_134453_room_facilities_edit extends Migration
{
    public function up()
    {
        $this->batchInsert('{{%room_facilities}}', ['name_ru', 'name_en', 'important', 'order'],[
            ['посудомоечная машина', 'dishwashing machine', 0, 200],
            ['стиральная машина', 'washing machine', 0, 210],
            ['Wi-Fi', 'Wi-Fi', 0, 220],
            ['мраморные полы', 'marble floors', 0, 230],
            ['ламинированные полы', 'laminated foors', 0, 240],
            ['24/7 cервис в номер', '24/7 room service', 0, 250],
            ['банные принадлежности в ванной', 'free toiletries', 0, 260],
        ]);

    }

    public function down()
    {
        echo "m150813_134453_room_facilities_edit cannot be reverted.\n";

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
