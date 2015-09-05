<?php

namespace common\models;

use Yii;
use \common\components\ListPriceType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%room}}".
 *
 * @property integer $id
 * @property integer $hotel_id
 * @property string  $title_ru
 * @property string  $title_en
 * @property string  $description_ru
 * @property string  $description_en
 * @property integer $adults
 * @property integer $children
 * @property integer $total
 * @property integer $active
 * @property integer $price_type
 * @property Hotel   $hotel
 * @property integer $amount
 */
class Room extends \yii\db\ActiveRecord
{
    public function beforeDelete() {
        if (parent::beforeDelete()) {
            foreach ($this->images as $image) {
                $image->delete();
            };
            $facilities = $this->facilities;
            foreach ($facilities as $f) {
                $this->unlink('facilities', $f, true);
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hotel_id', 'price_type'], 'required'],
            [['title_ru'], 'required', 'when' => function($model) {
                return empty($model->title_en);
            }, 'whenClient' => "function (attribute, value) {
                return !$('#room-title_en').val();
            }"],
            [['title_en'], 'required', 'when' => function($model) {
                return empty($model->title_ru);
            }, 'whenClient' => "function (attribute, value) {
                return !$('#room-title_ru').val();
            }"],
            [['description_ru'], 'required', 'when' => function($model) {
                return empty($model->description_en);
            }, 'whenClient' => "function (attribute, value) {
                return !$('#room-description_en').val();
            }"],
            [['description_en'], 'required', 'when' => function($model) {
                return empty($model->description_ru);
            }, 'whenClient' => "function (attribute, value) {
                return !$('#room-description_ru').val();
            }"],
            [['hotel_id', 'adults', 'children', 'total', 'active', 'amount'], 'integer'],
            [['price_type'], 'integer', 'min' => 1, 'max' => 2],
            [['description_ru', 'description_en'], 'string'],
            [['title_ru', 'title_en'], 'string', 'max' => 255]
        ];
    }


    public function fields() {
        $fields = parent::fields();
        $fields['facilities'] = function ($model) {
            return $model->facilities;
        };
        return $fields;
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'             => Yii::t('rooms', 'ID'),
			'hotel_id'       => Yii::t('rooms', 'Hotel ID'),
			'title_ru'       => Yii::t('rooms', 'Title Ru'),
			'title_en'       => Yii::t('rooms', 'Title En'),
			'description_ru' => Yii::t('rooms', 'Description Ru'),
			'description_en' => Yii::t('rooms', 'Description En'),
			'adults'         => Yii::t('rooms', 'Adults'),
			'children'       => Yii::t('rooms', 'Children'),
			'total'          => Yii::t('rooms', 'Total'),
			'price_type'     => Yii::t('rooms', 'Price type'),
			'active'         => Yii::t('rooms', 'Active'),
			'amount'         => Yii::t('rooms', 'Amount'),
		];
	}

    public function getHotel() {
        return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
    }
    
    public function getFacilities() {
        return $this->hasMany('\common\models\RoomFacilities', ['id' => 'facility_id'])
            ->viaTable('rel_room_facility', ['room_id' => 'id']);
    }

    public function getImages() {
        return $this->hasMany('\common\models\RoomImage', ['room_id' => 'id']);
    }

    public function getPricetype() {
        return ListPriceType::getOption($this->price_type);
        //return $this->hasOne(\common\components\ListPriceType::className(), ['id' => 'price_type']);
    }

    /**
     * Возвращает массив особенностей номера [id => поле заданное через $name]
     *
     * @param mixed $name
     * @param mixed $lang
     */
    public function facilityArray($name = 'name', $lang = false) {
        $flist = $this->facilities;
        $a = [];
        if ($name == 'name') {
            $name = $lang ? 'name_'.$lang : 'name_' . Lang::$current->url;
        }
        foreach ($flist as $f) {
            $a[$f->id] = $f[$name];
        }
        return $a;
    }

    public function getLangAttribute($attr, $lang = false) {
        $name = $lang ? $attr . '_' . $lang : $attr . '_' . Lang::$current->url;
        if (!isset($this->$name)) {
            return false;
        }
        return $this->$name;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            // заполняем количество на год вперед
            $attributes = [
                'date',
                'room_id',
                'count',
                'stop_sale',
            ];

            $rows = [];
            $default_row = [
                date('Y-m-d'),
                $this->id,
                $this->amount,
                0,
            ];
            $date = new \DateTime();
            for ($i = 0; $i <= 365; $i++) {
                $row = $default_row;
                $row[0] = $date->format('Y-m-d');
                $rows[] = $row;
                $date->modify('+1 day');
            }

            \Yii::$app->db->createCommand()
                ->batchInsert(RoomAvailability::tableName(), $attributes, $rows)
                ->execute();

            // Сигнал для системы сообщений
            if (isset(\Yii::$app->automaticSystemMessages)) {
                \Yii::$app->automaticSystemMessages->setDataUpdated();
            }
        }
    }

    public function afterDelete() {
        parent::afterDelete();
        // Сигнал для системы сообщений
        if (isset(\Yii::$app->automaticSystemMessages)) {
            \Yii::$app->automaticSystemMessages->setDataUpdated();
        }
    }

    /**
     * Определяет доступен ли номер для показа на frontend
     */
    public function published() {
        if (!$this->images) {
            return false;
        }
        return true;
    }

}
