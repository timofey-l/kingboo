<?php

use yii\db\Schema;
use yii\db\Migration;

class m150212_211856_createLookupTables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // TABLE: lookup_fields
        $this->createTable('{{%lookup_fields}}',[
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING. '(255) NOT NULL UNIQUE',
        ]);

        //TABLE: lookup_values
        $this->createTable('{{%lookup_values}}',[
            'id' => Schema::TYPE_PK,
            'lookup_field_id' => Schema::TYPE_INTEGER,
            'value_ru' => Schema::TYPE_STRING,
            'value_en' => Schema::TYPE_STRING,
        ]);

        // TABLE: lookup_field_values
        $this->createTable('{{%lookup_field_values}}',[
            'field_id' => Schema::TYPE_INTEGER,
            'value_id' => Schema::TYPE_INTEGER,
        ]);
        $this->addPrimaryKey('field_value', '{{%lookup_field_values}}', ['field_id', 'value_id']);

    }

    public function down()
    {
        $this->dropTable('{{%lookup_fields}}');
        $this->dropTable('{{%lookup_values}}');
        $this->dropTable('{{%lookup_field_values}}');
    }
}
