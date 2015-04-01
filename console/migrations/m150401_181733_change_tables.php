<?php

use yii\db\Schema;
use yii\db\Migration;

class m150401_181733_change_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // update hotels table
        // descriptions
        $this->renameColumn('{{%hotel}}', 'description', 'description_ru');
        $this->addColumn('{{%hotel}}', 'description_en', Schema::TYPE_TEXT);
        // titles
        $this->addColumn('{{%hotel}}', 'title_ru', Schema::TYPE_STRING . '(255) NOT NULL');
        $this->addColumn('{{%hotel}}', 'title_en', Schema::TYPE_STRING . '(255) NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%hotel}}', 'description_en');
        $this->renameColumn('{{%hotel}}', 'description_ru', 'description');
        $this->dropColumn('{{%hotel}}', 'title_ru');
        $this->dropColumn('{{%hotel}}', 'title_en');
    }
}
