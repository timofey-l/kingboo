<?php

use yii\db\Schema;
use yii\db\Migration;

class m150702_141004_add_currency_format extends Migration
{
    public function up()
    {
        $this->addColumn('{{%currencies}}', 'format', Schema::TYPE_STRING . ' NOT NULL');

        //rub
        if ($m = \common\models\Currency::findOne(['code' => 'RUB'])) {
            $m->format = '{value}{symbol}';
            $m->save();
        }
        //usd
        if ($m = \common\models\Currency::findOne(['code' => 'USD'])) {
            $m->format = '{symbol}{value}';
            $m->save();
        }
        //eur
        if ($m = \common\models\Currency::findOne(['code' => 'EUR'])) {
            $m->format = '{symbol}{value}';
            $m->save();
        }

    }

    public function down()
    {
        $this->dropColumn('{{%currencies}}', 'format');

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
