<?php

use common\models\BillingExpense;
use common\models\BillingIncome;
use yii\db\Schema;
use yii\db\Migration;

class m150908_062431_add_currency_to_invoice extends Migration
{
    public function up()
    {
        $this->addColumn(BillingIncome::tableName(), 'currency_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
        $this->addColumn(BillingExpense::tableName(), 'currency_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');

        foreach(BillingIncome::find()->all() as $income) {
            $income->currency_id = $income->invoice->currency_id;
            $income->save(false);
        }

        foreach(BillingExpense::find()->all() as $expense) {
            $expense->currency_id = $expense->service->currency_id;
            $expense->save(false);
        }
    }

    public function down()
    {
        $this->dropColumn(BillingIncome::tableName(), 'currency_id');
        $this->dropColumn(BillingExpense::tableName(), 'currency_id');

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
