<?php

use common\models\BillingExpense;
use yii\db\Schema;
use yii\db\Migration;

class m151012_104707_add_created_at_field_to_expenses extends Migration
{
    public function up()
    {
        $this->addColumn(BillingExpense::tableName(), 'created_at', Schema::TYPE_DATETIME . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(BillingExpense::tableName(), 'created_at');
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
