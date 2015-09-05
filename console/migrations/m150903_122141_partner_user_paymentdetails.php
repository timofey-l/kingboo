<?php

use yii\db\Schema;
use yii\db\Migration;

class m150903_122141_partner_user_paymentdetails extends Migration
{
    public function up()
    {
        $this->addColumn('{{%partner_user}}', 'payment_details', Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('{{%partner_user}}', 'payment_details');
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
