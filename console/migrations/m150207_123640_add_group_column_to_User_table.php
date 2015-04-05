<?php

use yii\db\Schema;
use yii\db\Migration;

class m150207_123640_add_group_column_to_User_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->addColumn('{{%user}}', 'group', 'integer');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'group');
    }
}
