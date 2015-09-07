<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_111121_change_billing_tables extends Migration
{
    public function up()
    {
        //$this->renameColumn('{{%billing_services}}', 'currency', 'currency_id');

        $this->renameColumn('{{%billing_services}}', 'name', 'name_ru');
        $this->addColumn('{{%billing_services}}', 'name_en', Schema::TYPE_STRING);

        $this->renameColumn('{{%billing_services}}', 'description', 'description_ru');
        $this->addColumn('{{%billing_services}}', 'description_en', Schema::TYPE_TEXT);

        $this->renameColumn('{{billing_income}}', 'pays_id', 'invoice_id');

        $this->renameColumn('{{billing_invoices}}', 'currency', 'currency_id');

        $this->dropColumn('{{%billing_pays_yandex}}', 'billing_invoice_id');
        $this->addColumn('{{billing_pays_yandex}}', 'billing_invoice_id', Schema::TYPE_INTEGER . ' NOT NULL');
    }

    public function down()
    {

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
