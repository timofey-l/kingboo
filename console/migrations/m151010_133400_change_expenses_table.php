<?php

use common\models\BillingExpense;
use yii\db\Schema;
use yii\db\Migration;

class m151010_133400_change_expenses_table extends Migration
{
    public function up()
    {
        // чистим таблицу
        $this->truncateTable(BillingExpense::tableName());

        // добавляем поле frozen
        $this->addColumn(BillingExpense::tableName(), 'frozen', Schema::TYPE_BOOLEAN);

        // изменяем поле date
        $this->alterColumn(BillingExpense::tableName(), 'date', Schema::TYPE_DATE);

        // добавляем поле date_end (используется для указания периода)
        $this->addColumn(BillingExpense::tableName(), 'date_end', Schema::TYPE_DATE);

    }

    public function down()
    {
        $this->dropColumn(BillingExpense::tableName(), 'frozen');
        $this->dropColumn(BillingExpense::tableName(), 'date_end');
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
