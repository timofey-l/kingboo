<?php

use yii\db\Schema;
use yii\db\Migration;

class m151111_130252_add_widgetcall_field extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Hotel::tableName(), 'widgetcall_enabled', Schema::TYPE_BOOLEAN . ' DEFAULT FALSE');
        $this->addColumn(\common\models\Hotel::tableName(), 'widgetcall_text', Schema::TYPE_TEXT . " DEFAULT ''");
    }

    public function down()
    {
        $this->dropColumn(\common\models\Hotel::tableName(), 'widgetcall_enabled');
        $this->dropColumn(\common\models\Hotel::tableName(), 'widgetcall_text');
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
