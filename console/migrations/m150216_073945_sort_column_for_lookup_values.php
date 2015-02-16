<?php

use yii\db\Schema;
use yii\db\Migration;

class m150216_073945_sort_column_for_lookup_values extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->addColumn('{{%lookup_values}}', 'sort', 'integer');
    }

    public function down()
    {
        $this->dropColumn('{{%lookup_values}}', 'sort');
    }
}
