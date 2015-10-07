<?php

use common\models\Hotel;
use yii\db\Schema;
use yii\db\Migration;

class m151007_174158_add_freeze_time_to_hotel extends Migration
{
    public function up()
    {
        $this->addColumn(Hotel::tableName(), 'freeze_time', Schema::TYPE_DATETIME);
    }

    public function down()
    {
        $this->dropColumn(Hotel::tableName(), 'freeze_time');
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
