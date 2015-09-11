<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_122834_faq_table_add extends Migration
{
    public function up()
    {
        $this->createTable('{{%faq}}', [
            'id' => Schema::TYPE_PK,
            'order' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title_ru' => Schema::TYPE_STRING . ' DEFAULT \'\'',
            'title_en' => Schema::TYPE_STRING . ' DEFAULT \'\'',
            'content_ru' => Schema::TYPE_TEXT . ' DEFAULT \'\'',
            'content_en' => Schema::TYPE_TEXT . ' DEFAULT \'\'',
            'active' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%faq}}');
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
