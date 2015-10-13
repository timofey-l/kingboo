<?php

use common\models\BillingAccountServices;
use yii\db\Schema;
use yii\db\Migration;

class m151013_082623_add_created_at_to_account_services extends Migration
{
    public function up()
    {
        $this->addColumn(BillingAccountServices::tableName(), 'created_at', Schema::TYPE_DATETIME . ' NOT NULL DEFAULT NOW()');
    }

    public function down()
    {
        $this->dropColumn(BillingAccountServices::tableName(), 'created_at');
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
