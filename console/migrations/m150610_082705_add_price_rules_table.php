<?php

use yii\db\Migration;
use yii\db\Schema;

class m150610_082705_add_price_rules_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%price_rules}}', [
            'id' => Schema::TYPE_PK,
            'dateFrom' => Schema::TYPE_DATE,
            'dateTo' => Schema::TYPE_DATE,
            'dateFromB' => Schema::TYPE_DATE,
            'dateToB' => Schema::TYPE_DATE,
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',

            'value' => Schema::TYPE_DOUBLE . ' NOT NULL',
            'valueType' => Schema::TYPE_INTEGER . ' NOT NULL',

            'counter' => Schema::TYPE_INTEGER . ' NOT NULL',

            'additive' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            'active' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',

            'minSum' => Schema::TYPE_DOUBLE . ' DEFAULT NULL',
            'maxSum' => Schema::TYPE_DOUBLE . ' DEFAULT NULL',

            'params' => Schema::TYPE_TEXT,
        ], $tableOptions);

        $this->createTable('{{%price_rules_rooms}}', [
            'id' => Schema::TYPE_PK,
            'price_rule_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'room_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%price_rules}}');
        $this->dropTable('{{%price_rules_rooms}}');
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
