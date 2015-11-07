<?php

namespace common\models;

use partner\models\PartnerUser;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%hotel}}".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string  $name
 * @property boolean  $ru
 * @property boolean  $en
 * @property string  $address_ru
 * @property string  $address_en
 * @property string  $contact_email
 * @property string  $contact_phone
 * @property string  $lng
 * @property string  $lat
 * @property string  $description_ru
 * @property integer $category
 * @property string  $timezone
 * @property string  $description_en
 * @property string  $title_ru
 * @property string  $title_en
 * @property boolean $allow_partial_pay
 * @property string  $partial_pay_percent
 * @property boolean $frozen
 * @property string  $freeze_time
 */
class Hotel extends \yii\db\ActiveRecord
{
	const MIN_PART_PAY = 10;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%hotel}}';
	}

	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			foreach ($this->rooms as $room) {
				$room->delete();
			};
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

    public function afterDelete() {
        parent::afterDelete();
        // Сигнал для системы сообщений
        if (isset(\Yii::$app->automaticSystemMessages)) {
        	\Yii::$app->automaticSystemMessages->setDataUpdated();
        }
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
    	parent::afterSave($insert, $changedAttributes);

    	// Если изменилась валюта, удаляем будущие цены для всех номеров
    	if (isset($changedAttributes['currency_id']) && $changedAttributes['currency_id'] != $this->currency_id) {
    		foreach ($this->rooms as $room) {
    			\common\models\RoomPrices::deleteFuturePrices($room);
    		}
    	}

        // если флаг заморозки был изменен на true
        if (isset($changedAttributes['frozen']) && $changedAttributes['frozen'] == false && $this->frozen == true) {
            $this->freeze_time = date(\DateTime::ISO8601);
            $this->update(false, ['freeze_time']);
        }

        // если флаг заморозки был изменен на false
        if (isset($changedAttributes['frozen']) && $changedAttributes['frozen'] == true && $this->frozen == false) {
            $this->freeze_time = null;
            $this->update(false, ['freeze_time']);
        }

        if ($insert) {
            // ищем тариф по умолчанию и добавляем его к отелю
            $partner = PartnerUser::findOne(\Yii::$app->user->id);
            $billingAccount = $partner->account;
            $service = BillingService::findOne(['default' => 1]);
            if ($service !== null) {
                $accountService = new BillingAccountServices;
                $accountService->account_id = $billingAccount->id;
                $accountService->service_id = $service->id;
                $accountService->hotel_id = $this->id;
                $accountService->active = true;
                $accountService->add_date = date(\DateTime::ISO8601);
                $accountService->end_date = date(\DateTime::ISO8601);
                $accountService->save();
            }

            // Сигнал для системы сообщений
			if (isset(\Yii::$app->automaticSystemMessages)) {
				\Yii::$app->automaticSystemMessages->setDataUpdated();
			}
        }
    }

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
            [['freeze_time'], 'string'],
			[['partner_id', 'name', 'currency_id'], 'required'],
			[['ru'], 'integer', 'min' => 1, 'when' => function ($model) {
				return ($model->en == 0);
			}],
			[['en'], 'integer', 'min' => 1, 'when' => function ($model) {
				return ($model->ru == 0);
			}],
			[['title_ru', 'address_ru'], 'required', 'when' => function ($model) {
				return $model->ru == 1;
			}, 'whenClient' => "function (attribute, value) {
                return $('#hotel-ru').prop('checked');
            }"],
			[['title_en', 'address_en'], 'required', 'when' => function ($model) {
				return $model->en > 1;
			}, 'whenClient' => "function (attribute, value) {
                return $('#hotel-en').prop('checked');
            }"],
			[['partner_id', 'category'], 'integer'],
			[['lng', 'lat'], 'number'],
			[['description_ru', 'description_en', 'domain'], 'string'],
			[['name', 'address_ru', 'address_en', 'timezone', 'title_ru', 'title_en', 'contact_email', 'contact_phone'], 'string', 'max' => 255],
			[['lng', 'lat'], 'default', 'value' => 0],
			[['currency_id'], 'integer', 'min' => 1],
			[['ru', 'en', 'allow_partial_pay'], 'integer', 'max' => 1, 'min' => 0],
			['partial_pay_percent', 'integer', 'min' => self::MIN_PART_PAY, 'max' => 100],
            ['name', 'unique'],
            ['domain', 'unique'],
			['frozen', 'default', 'value' => 0],
//			['frozen', 'integer'],
			['name', 'in', 'not' => true, 'range' => ['partner', 'backend']],
		];
	}

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'name',
            ],
        ];
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                  => Yii::t('hotels', 'ID'),
			'partner_id'          => Yii::t('hotels', 'Partner ID'),
			'name'                => Yii::t('hotels', 'URL'),
			'ru'          		  => Yii::t('hotels', 'Russian'),
			'en'          		  => Yii::t('hotels', 'English'),
			'address_ru'          => Yii::t('hotels', 'Hotel address Ru'),
			'address_en'          => Yii::t('hotels', 'Hotel address En'),
			'lng'                 => Yii::t('hotels', 'Lng'),
			'lat'                 => Yii::t('hotels', 'Lat'),
			'currency_id'         => Yii::t('hotels', 'Currency'),
			'description_ru'      => Yii::t('hotels', 'Description Ru'),
			'category'            => Yii::t('hotels', 'Category'),
			'timezone'            => Yii::t('hotels', 'Timezone'),
			'description_en'      => Yii::t('hotels', 'Description En'),
			'title_ru'            => Yii::t('hotels', 'Title Ru'),
			'title_en'            => Yii::t('hotels', 'Title En'),
			'allow_partial_pay'   => Yii::t('hotels', 'Allow partial advance payment'),
			'partial_pay_percent' => Yii::t('hotels', 'Percents to pay'),
			'domain'              => Yii::t('hotels', 'Domain name of your hotel'),
			'contact_email'       => Yii::t('hotels', 'Contact email'),
			'contact_phone'       => Yii::t('hotels', 'Contact phone'),
		];
	}

    public function attributeHints()
    {
        return [];
    }

    public function attributePopover($key=false)
    {
    	$a = [
            'name' => Yii::t('hotels', 'Create and enter the address of your hotel in system king.boo.com. Ex. palm-beach-hotel.'),
            'domain' => Yii::t('hotels', 'Fill in this field, if you want to register already existing personal domain of your hotel in king.boo system. If you still don’t have but want to register personal domain name for your hotel, please, apply for registration'),
            'currency_id' =>Yii::t('hotels', 'The currency in which prices for hotel rooms are shown.<br /><b class="text-red">Attention!</b><br />If you change hotel currency all room prices will be deleted!'),
        ];
    	if ($key) {
    		if (isset($a[$key])) {
    			return $a[$key];
    		} else {
    			return '';
    		}
    	} else {
        	return $a;
    	}
    }

	public function getRooms()
	{
		return $this->hasMany('\common\models\Room', ['hotel_id' => 'id']);
	}

	public function getImages()
	{
		return $this->hasMany('\common\models\HotelImage', ['hotel_id' => 'id']);
	}

	public function getCurrency()
	{
		return $this->hasOne('\common\models\Currency', ['id' => 'currency_id']);
	}

	public function getFacilities()
	{
		return $this->hasMany('\common\models\HotelFacilities', ['id' => 'facility_id'])
			->viaTable('rel_hotel_facility', ['hotel_id' => 'id']);
	}

	/**
	 * Возвращает системный URL отеля (http://hotel.king-boo.com)
	 */
	public function getLocal_url() {
		$server = 'king-boo.com';
		$hostInfo = \Yii::$app->request->getHostInfo();
		if (preg_match('%partner\.(?<server>.+)$%', $hostInfo, $m)) {
    		$server = $m['server'];
		}		
		return 'http://' . $this->name . '.' . $server;
	}

	/**
	 * Возвращает массив особенностей отеля [id => поле заданное через $name]
	 *
	 * @param mixed $name
	 * @param mixed $lang
	 * @return array
	 */
	public function facilityArray($name = 'name', $lang = false)
	{
		$flist = $this->facilities;
		$a = [];
		if ($name == 'name') {
			$name = $lang ? 'name_' . $lang : 'name_' . Lang::$current->url;
		}
		foreach ($flist as $f) {
			$a[$f->id] = $f[$name];
		}

		return $a;
	}

	public function getPartner()
	{
		return $this->hasOne('\partner\models\PartnerUser', ['id' => 'partner_id']);
	}

    /*public function getLangAttribute($attr, $lang = false) {
        $name = $lang ? $attr . '_' . $lang : $attr . '_' . Lang::$current->url;
        if (!isset($this->$name)) {
            return false;
        }
        return $this->$name;
    }*/

    /**
     * Возвращает свойство объекта с учетом языка
     * 
     * @param string $property - имя свойства
     * @param string $lang - язык, если не задан используется системный
     * @return mixed
     */
    public function property($property, $lang = false) {
    	if (isset($this->$property)) {
    		return $this->$property;
    	}
        $name = $lang ? $property . '_' . $lang : $property . '_' . Lang::$current->url;
        if (!isset($this->$name)) {
            return null;
        }
        return $this->$name;
    }

    /**
     * Определяет доступен ли отель для показа на frontend
     *
     * param @lang - язык, если указан, определяет опубликованность этого языка
     * return boolean
     */
    public function published($lang = false) {
        if ($this->frozen) {
            return false;
        }
        if (!$this->rooms) {
            return false;
        }
        if (!$this->images) {
            return false;
        }
        if ($this->partner->isBlocked()) {
        	return false;
        }
        if ($lang) {
        	if (!$this->$lang) {
        		return false;
        	}
        }
        return true;
    }

}
