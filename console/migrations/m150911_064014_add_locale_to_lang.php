<?php

use common\models\Lang;
use yii\db\Schema;
use yii\db\Migration;

class m150911_064014_add_locale_to_lang extends Migration
{
    public function up()
    {
        $this->addColumn(Lang::tableName(), 'locale', Schema::TYPE_STRING . ' DEFAULT \'\'');

        $this->update(Lang::tableName(), ['locale' => 'ru_RU.UTF-8'], ['url' => 'ru']);
        $this->update(Lang::tableName(), ['locale' => 'en_US.UTF-8'], ['url' => 'en']);
    }

    public function down()
    {
        $this->dropColumn(Lang::tableName(), 'locale');
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
