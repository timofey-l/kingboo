<?php

use common\models\BillingAccount;
use common\models\Currency;
use partner\models\PartnerUser;
use yii\db\Schema;
use yii\db\Migration;

class m150824_053524_add_billing_tables extends Migration
{
    public function up()
    {
        // services
        $this->createTable('{{%billing_services}}',[
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'archived' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'default' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'monthly' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'unique' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'currency' => Schema::TYPE_INTEGER . ' DEFAULT ' . Currency::findOne(['code' => 'RUB'])->id,
            'enable_cost' => Schema::TYPE_FLOAT . ' DEFAULT 0',
            'monthly_cost' => Schema::TYPE_FLOAT . ' DEFAULT 0',
        ]);

        // income
        $this->createTable('{{%billing_income}}', [
            'id' => Schema::TYPE_PK,
            'sum' => Schema::TYPE_FLOAT . ' NOT NULL',
            'date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'account_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'pays_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        // expenses
        $this->createTable('{{%billing_expenses}}', [
            'id' => Schema::TYPE_PK,
            'sum' => Schema::TYPE_FLOAT . ' NOT NULL',
            'date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'account_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'service_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'comment' => Schema::TYPE_TEXT . " DEFAULT ''",
        ]);

        // account
        $this->createTable('{{%billing_account}}', [
            'id' => Schema::TYPE_PK,
            'partner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'balance' => Schema::TYPE_FLOAT . ' DEFAULT 0',
        ]);

        // account services
        $this->createTable('{{%billing_account_services}}', [
            'id' => Schema::TYPE_PK,
            'account_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'service_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'add_date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'end_date' => Schema::TYPE_DATETIME . ' NOT NULL',
            'active' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
        ]);

        // invoices
        $this->createTable('{{%billing_invoices}}', [
            'id' => Schema::TYPE_PK,
            'account_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sum' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'currency' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_DATETIME,
            'payed' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            'system' => Schema::TYPE_INTEGER . ' DEFAULT 0',
        ]);

        // Yandex Kassa pays
        $this->createTable('{{%billing_pays_yandex}}', [
            'id' => Schema::TYPE_PK,
            'invoiceId' => Schema::TYPE_BIGINT . ' NOT NULL',
            'payed' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            'checked' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            'billing_invoice_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'check_post_dump' => Schema::TYPE_TEXT,
            'avisio_post_dump' => Schema::TYPE_TEXT,
        ]);


        // добавление аккаунтов билинга к имеющимся PartnerUser
        // валюта в рублях
        $defaultCurrency = Currency::findOne(['code' => 'RUB']);
        foreach (PartnerUser::find()->all() as $partner) {
            $billing = new BillingAccount();
            $billing->currency_id = $defaultCurrency->id;
            $partner->link('billing', $billing);
        }

    }

    public function down()
    {
        echo "m150824_053524_add_billing_tables cannot be reverted.\n";

        return false;
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
