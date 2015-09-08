<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_item}}".
 *
 * @property integer $id
 * @property integer $room_id
 * @property integer $order_id
 * @property integer $adults
 * @property integer $children
 * @property integer $kids
 * @property double $sum
 * @property double $sum_currency_id
 * @property double $pay_sum
 * @property double $pay_sum_currency_id
 * @property double $payment_system_sum
 * @property double $payment_system_sum_currency_id
 * @property string $guest_name
 * @property string $guest_surname
 * @property integer $guest_address
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id', 'order_id', 'adults', 'children', 'kids', 'sum', 'guest_name', 'guest_surname', 'guest_address'], 'required'],
            [['room_id', 'order_id', 'adults', 'children', 'kids', 'guest_address', 'sum_currency_id', 'pay_sum_currency_id', 'payment_system_sum_currency_id'], 'integer'],
            [['sum', 'pay_sum', 'payment_system_sum'], 'number'],
            [['guest_name', 'guest_surname'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('orders', 'ID'),
            'room_id' => Yii::t('orders', 'Room ID'),
            'order_id' => Yii::t('orders', 'Order ID'),
            'adults' => Yii::t('orders', 'Adults'),
            'children' => Yii::t('orders', 'Children'),
            'kids' => Yii::t('orders', 'Kids'),
            'sum' => Yii::t('orders', 'Sum'),
            'guest_name' => Yii::t('orders', 'Name'),
            'guest_surname' => Yii::t('orders', 'Surname'),
            'guest_address' => Yii::t('orders', 'Address'),
        ];
    }

	public function afterSave($insert, $changedAttributes) {
		if ($insert) {
			// минус один номер из фонда на весь срок бронирования
			/** @var Order $order */
			$order = Order::findOne($this->order_id);
			$roomAvailabilityArray = RoomAvailability::find()
				->where(['>=', 'date', $order->dateFrom])
				->andWhere(['<', 'date', $order->dateTo])
				->andWhere([
					'room_id' => $this->room_id,
				])->all();
			if (is_array($roomAvailabilityArray)) {
				foreach ($roomAvailabilityArray as $ra) {
					$ra->count = $ra->count - 1;
					$ra->save(false);
				}
			}
		}

		parent::afterSave($insert, $changedAttributes);
	}

	public function getRoom() {
		return $this->hasOne(Room::className(), ['id' => 'room_id']);
	}
}
