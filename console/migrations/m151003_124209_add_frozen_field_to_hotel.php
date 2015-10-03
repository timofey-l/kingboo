<?php

use common\models\Hotel;
use yii\db\Schema;
use yii\db\Migration;

class m151003_124209_add_frozen_field_to_hotel extends Migration
{
    public function up()
    {
        $this->addColumn(Hotel::tableName(), 'frozen', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE');
    }

    public function down()
    {
        $this->dropColumn(Hotel::tableName(), 'frozen');
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
