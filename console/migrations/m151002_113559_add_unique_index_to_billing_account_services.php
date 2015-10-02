<?php

use common\models\BillingAccountServices;
use yii\db\Schema;
use yii\db\Migration;

class m151002_113559_add_unique_index_to_billing_account_services extends Migration
{
    public function up()
    {
        $this->createIndex('account_service_hotel', BillingAccountServices::tableName(), ['account_id', 'service_id', 'hotel_id'], true);
    }

    public function down()
    {
        $this->dropIndex('account_service_hotel', BillingAccountServices::tableName());
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
