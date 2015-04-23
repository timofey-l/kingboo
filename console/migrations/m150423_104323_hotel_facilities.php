<?php

use yii\db\Schema;
use yii\db\Migration;

class m150423_104323_hotel_facilities extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%hotel_facilities}}', [
            'id' => Schema::TYPE_PK,
            'name_ru' => Schema::TYPE_STRING . '(255) NOT NULL',
            'name_en' => Schema::TYPE_STRING . '(255) NOT NULL',
            'group_id' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'important' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'order' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('nameRu', '{{%hotel_facilities}}', ['group_id', 'name_ru'], true);
        $this->createIndex('nameEn', '{{%hotel_facilities}}', ['group_id', 'name_en'], true);
        
        $this->batchInsert('{{%hotel_facilities}}', ['name_ru', 'name_en', 'group_id', 'important', 'order'],[
            ['кондиционер', 'air condition', 1, 0, 10],
            ['ресепшин', 'reception', 1, 0, 20],
            ['24 часа заселение', '24 hours check-in', 1, 0, 30],
            ['24 часа ресепшн', '24 hours reception', 1, 0, 40],
            ['сейф', 'safe', 1, 0, 50],
            ['обмен валют', 'currency exchange', 1, 0, 60],
            ['гардероб', 'cloakroom', 1, 0, 70],
            ['лифт', 'elevator', 1, 0, 80],
            ['маркет', 'market', 1, 0, 90],
            ['магазины', 'shops', 1, 0, 100],
            ['парикмахер', 'hair dresser', 1, 0, 110],
            ['амфитеатр', 'amphi theater', 1, 0, 120],
            ['конференс зал', 'conference hall', 1, 0, 130],
            ['казино', 'casino', 1, 0, 140],
            ['игровой зал', 'game room', 1, 0, 150],
            ['TV зал', 'tv room', 1, 0, 160],
            ['интернет клуб', 'internet hall', 1, 0, 170],
            ['wi-fi', 'wi-fi', 1, 0, 180],
            ['обслуживание номеров', 'room service', 1, 0, 190],
            ['услуги прачечной', 'laundry service', 1, 0, 200],
            ['доктор', 'doctor', 1, 0, 210],
            ['парковка', 'car park', 1, 0, 220],
            ['мини клуб', 'mini club', 1, 0, 230],
            ['няня', 'baby sitter', 1, 0, 240],
            ['открытый бассейн', 'outdoor pool', 1, 0, 250],
            ['закрытый бассейн', 'indoor pool', 1, 0, 260],
            ['бассейн с морской водой', 'salt water pool', 1, 0, 270],
            ['бассейн с подогревом', 'heated pool', 1, 0, 280],
            ['детский бассейн', 'childrens pool', 1, 0, 290],
            ['аквапарк', 'aquapark', 1, 0, 300],
            ['турецкая баня', 'turkish bath', 1, 0, 310],
            ['сауна', 'sauna', 1, 0, 320],
            ['СПА', 'spa', 1, 0, 330],
            ['массаж', 'massage', 1, 0, 340],
            ['фитнес центр', 'fitness center', 1, 0, 350],
            
            ['городской отель', 'city hotel ', 2, 0, 500],
            ['курортный отель', 'resort', 2, 0, 510],
            ['пляжный отель', 'beach hotel', 2, 0, 520],
            ['апарт отель', 'apart hotel', 2, 0, 530],
            ['апартмент отель', 'apartment hotel', 2, 0, 540],
            ['бунгало', 'bungalow', 2, 0, 550],
            ['хостел', 'hostel', 2, 0, 560],
            ['бутик отель', 'boutique hotel', 2, 0, 570],
            ['деревенский отель', 'countryside hotel', 2, 0, 580],
            ['курортный поселок', 'holiday village', 2, 0, 590],
            ['вилла', 'villa', 2, 0, 600],
            ['СПА-отель', 'spa hotel', 2, 0, 610],
            ['гольф отель', 'golf hotel', 2, 0, 620],
            ['казино отель', 'casino hotel', 2, 0, 630],
            ['аэропортовый отель', 'airport hotel', 2, 0, 640],
            ['бизнес отель', 'business hotel', 2, 0, 650],
            ['конгресс отель', 'conference and meeting hotel', 2, 0, 660],
            ['исторический отель', 'historical hotel', 2, 0, 670],
            ['молодежный отель', 'youth hotel', 2, 0, 680],
            ['горнолыжный курорт', 'ski resort', 2, 0, 690],
            ['термальный отель', 'thermal hotel', 2, 0, 700],
            ['семейный отель', 'family hotel', 2, 0, 710],
            ['дизайн отель ', 'design hotel', 2, 0, 720],
            ['тематический отель', 'theme hotel', 2, 0, 730],
            ['пещерный отель', 'cave hotel', 2, 0, 740],
            ['каменный отель', 'stone hotel', 2, 0, 750],
            
            ['водные виды спорта', 'water sports', 3, 0, 1010],
            ['банан', 'banana', 3, 0, 1020],
            ['водные лыжи', 'water ski', 3, 0, 1030],
            ['jetski', 'jetski', 3, 0, 1040],
            ['дайвинг', 'scuba diving', 3, 0, 1050],
            ['виндсерфинг', 'wind surf', 3, 0, 1060],
            ['каное', 'canoe', 3, 0, 1070],
            ['футбол', 'football', 3, 0, 1080],
            ['тенис', 'tennis', 3, 0, 1090],
            ['настольный тенис', 'table tennis', 3, 0, 1100],
            ['сквош', 'squash', 3, 0, 1110],
            ['стрельба из лука', 'archery', 3, 0, 1120],
            ['катание на лошадях', 'horse riding', 3, 0, 1130],
            ['аэробика', 'aerobics', 3, 0, 1140],
            ['велосипед', 'cycling', 3, 0, 1150],
            ['гандбол', 'handball', 3, 0, 1160],
            ['баскетбол', 'basketball', 3, 0, 1170],
            ['волейбол', 'volleyball', 3, 0, 1180],
            ['пляжный волейбол', 'beach volleyball', 3, 0, 1190],
            ['боча', 'boccia', 3, 0, 1200],
            ['боулинг', 'bowling', 3, 0, 1210],
            ['бильярд', 'billiards', 3, 0, 1220],
            ['мини гольф', 'mini golf', 3, 0, 1230],
            ['гольф', 'golf', 3, 0, 1240],
            ['анимация для взрослых', 'adult animation', 3, 0, 1250],
            ['детская анимация', 'childrens animation', 3, 0, 1260],
            ['горные лыжи', 'skiing', 3, 0, 1270],
            
            ['visa', 'visa', 4, 0, 1500],
            ['mastercard', 'mastercard', 4, 0, 1510],
            ['american express', 'american express', 4, 0, 1520],
            ['наличные', 'cash', 4, 0, 1530],
            
            ['песчанный пляж', 'sand beach', 5, 0, 2000],
            ['пляж с галькой', 'pebble beach', 5, 0, 2010],
            ['галечный пляж', 'stone beach', 5, 0, 2020],
            ['шезлонг', 'sun beds', 5, 0, 2030],
            ['тент', 'sun shades', 5, 0, 2040],
            ['зонт', 'parasols', 5, 0, 2050],
            ['бар на пляже', 'beach bar', 5, 0, 2060],
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%hotel_facilities}}');
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
