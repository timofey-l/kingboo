<?php

use common\models\BillingService;
use yii\db\Schema;
use yii\db\Migration;

class m151003_080939_add_yearly_cost_to_billing_services extends Migration
{
    public function up()
    {
        $this->addColumn(BillingService::tableName(), 'yearly_cost', Schema::TYPE_DOUBLE . ' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn(BillingService::tableName(), 'yearly_cost');
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
