<?php

use yii\db\Schema;
use yii\db\Migration;

class m150403_133047_add_fields_to_room_price_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{%room_price}}', 'adults', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
        $this->addColumn('{{%room_price}}', 'children', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
        $this->addColumn('{{%room_price}}', 'total', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('{{%room_price}}', 'adults');
        $this->dropColumn('{{%room_price}}', 'children');
        $this->dropColumn('{{%room_price}}', 'total');
    }
}
