<?php

use yii\db\Schema;
use yii\db\Migration;

class m150904_213717_add_pages extends Migration
{
    public function up()
    {
        $this->batchInsert('{{%pages}}', ['route', 'title_ru', 'title_en', 'content_ru', 'content_en', 'active'], [
            ['about', 'О сервисе', 'About service', '', '', 1],
            ['extended', 'Расширенные возможности', 'Extended opportunities', '', '', 1],
            ['plans', 'Тарифы', 'Subscription plans', '', '', 1],
            ['how-to-join', 'Как подключить', 'How to join', '', '', 1],
            ['guarantee', 'Гарантия безопасности', 'Guarantee of safety', '', '', 1],
            ['special', 'Акции и спецпредложения', 'Special offers', '', '', 1],
        ]);
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
