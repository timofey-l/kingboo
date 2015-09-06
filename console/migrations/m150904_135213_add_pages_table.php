<?php

use yii\db\Schema;
use yii\db\Migration;

class m150904_135213_add_pages_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%pages}}', [
            'id' => Schema::TYPE_PK,
            'route' => Schema::TYPE_STRING . ' NOT NULL',
            'title_ru' => Schema::TYPE_STRING . ' DEFAULT \'\'',
            'title_en' => Schema::TYPE_STRING . ' DEFAULT \'\'',
            'content_ru' => Schema::TYPE_TEXT . ' DEFAULT \'\'',
            'content_en' => Schema::TYPE_TEXT . ' DEFAULT \'\'',
            'active' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
        ]);
        // route index
        $this->createIndex('unique_route','{{%pages}}', ['route'], true);
    }

    public function down()
    {
        $this->dropIndex('unique_route', '{{%pages}}');
        $this->dropTable('{{%pages}}');
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
