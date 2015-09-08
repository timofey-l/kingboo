<?php

use partner\models\PartnerUser;
use yii\db\Schema;
use yii\db\Migration;

class m150908_102037_add_unique_index_to_partner_user extends Migration
{
    public function up()
    {
        $this->createIndex('email', PartnerUser::tableName(), ['email'], true);
    }

    public function down()
    {
        $this->dropIndex('email', PartnerUser::tableName());
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
