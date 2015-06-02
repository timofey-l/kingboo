<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $number
 * @property integer $status
 * @property string  $contact_email
 * @property string  $contact_phone
 * @property string  $contact_name
 * @property string  $contact_surname
 * @property integer $contact_address
 * @property string  $dateFrom
 * @property string  $dateTo
 * @property double  $sum
 * @property integer $partial_pay
 * @property integer $partial_pay_percent
 * @property double  $pay_sum
 * @property integer $hotel_id
 * @property string  $lang
 */
class Order extends ActiveRecord
{
	const OS_CANCELED = 0; // аннулирован
	const OS_WAITING_PAY = 1; // ожидает оплаты
	const OS_PAYED = 2; // оплачен

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%order}}';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class'      => TimestampBehavior::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
					ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['dateFrom', 'dateTo'], 'safe'],
			[['number', 'status', 'contact_email', 'contact_name', 'contact_surname', 'contact_address', 'dateFrom', 'dateTo', 'sum', 'partial_pay_percent', 'pay_sum'], 'required'],
			[['status', 'contact_address', 'partial_pay', 'partial_pay_percent', 'hotel_id'], 'integer'],
			[['sum', 'pay_sum'], 'number'],
			[['number'], 'string', 'max' => 32, 'min' => 32],
			[['number'], 'unique'],
			[['lang'], 'string', 'max' => 3],
			[['contact_email', 'contact_phone', 'contact_name', 'contact_surname'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                  => Yii::t('orders', 'ID'),
			'created_at'          => Yii::t('orders', 'Created At'),
			'updated_at'          => Yii::t('orders', 'Updated At'),
			'number'              => Yii::t('orders', 'Number'),
			'status'              => Yii::t('orders', 'Status'),
			'contact_email'       => Yii::t('orders', 'Email'),
			'contact_phone'       => Yii::t('orders', 'Phone'),
			'contact_name'        => Yii::t('orders', 'Name'),
			'contact_surname'     => Yii::t('orders', 'Surname'),
			'contact_address'     => Yii::t('orders', 'Address'),
			'dateFrom'            => Yii::t('orders', 'Date From'),
			'dateTo'              => Yii::t('orders', 'Date To'),
			'sum'                 => Yii::t('orders', 'Sum'),
			'partial_pay'         => Yii::t('orders', 'Partial Pay'),
			'partial_pay_percent' => Yii::t('orders', 'Partial Pay Percent'),
			'pay_sum'             => Yii::t('orders', 'Pay Sum'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttrs)
	{
		// проверяем изменение статуса уже созданого заказа
		if (!$insert && array_key_exists('status', $changedAttrs)) {
			switch ($this->status) {
				// если заказ был отменен - возвращаем его в номерной фонд за весь срок бронирования
				case static::OS_CANCELED:
					foreach ($this->orderItems as $orderItem) {
						/** @var OrderItem $orderItem */
						$roomAvailabilityArray = RoomAvailability::find()
							->where(['>=', 'date', $this->dateFrom])
							->andWhere(['<', 'date', $this->dateTo])
							->andWhere([
								'room_id' => $orderItem->room_id,
							])->all();
						if (is_array($roomAvailabilityArray)) {
							foreach ($roomAvailabilityArray as $ra) {
								$ra->count = $ra->count + 1;
								$ra->save(false);
							}
						}
					}
					break;
			}
		}
	}

	/**
	 * Связь с элементами заказа
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrderItems()
	{
		return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
	}

	/**
	 * Связь с отелем
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getHotel()
	{
		return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
	}
}
