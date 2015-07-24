<?php

use yii\db\Schema;
use yii\db\Migration;

class m150424_134510_room_facilities extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%room_facilities}}', [
            'id' => Schema::TYPE_PK,
            'name_ru' => Schema::TYPE_STRING . '(255) NOT NULL',
            'name_en' => Schema::TYPE_STRING . '(255) NOT NULL',
            'important' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'order' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

        //$this->createIndex('nameRu', '{{%room_facilities}}', ['name_ru'], true);
        //$this->createIndex('nameEn', '{{%room_facilities}}', ['name_en'], true);
        
        $this->batchInsert('{{%room_facilities}}', ['name_ru', 'name_en', 'important', 'order'],[
            ['ковровое покрытие', 'carpet floor', 0, 10],
            ['кондиционер', 'air conditioning', 0, 20],
            ['отопление', 'heating', 0, 30],
            ['горячая вода', 'hot water', 0, 40],
            ['душ', 'shower', 0, 50],
            ['ванна', 'bathtub', 0, 60],
            ['сейф', 'safe', 0, 70],
            ['балкон', 'balcony', 0, 80],
            ['терраса', 'terrace', 0, 90],
            ['ванна для инвалидов', 'handicapped bathroom', 0, 100],
            ['кресло-каталка', 'wheel chair access', 0, 110],
            ['обслуживание в номерах', 'room service', 0, 120],
            ['мини-бар', 'minibar', 0, 130],
            ['телефон', 'telephone', 0, 140],
            ['телевизор', ' tv', 0, 150],
            ['спутниковое телевидение', 'sattelite channels', 0, 160],
            ['интернет', 'internet', 0, 170],
            ['фен', 'hair dryer', 0, 180],
            ['кофемашина', ' tea-coffee machine', 0, 190],
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%room_facilities}}');
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
