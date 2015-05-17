<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_availability}}".
 *
 * @property integer $id
 * @property string $date
 * @property integer $room_id
 * @property integer $count
 * @property integer $availability
 */
class RoomAvailability extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_availability}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'room_id', 'count', 'availability'], 'required'],
            [['date'], 'safe'],
            [['room_id', 'count', 'availability'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rooms', 'ID'),
            'date' => Yii::t('rooms', 'Date'),
            'room_id' => Yii::t('rooms', 'Room ID'),
            'count' => Yii::t('rooms', 'Count'),
            'availability' => Yii::t('rooms', 'Availability'),
        ];
    }

    public function getRoom() {
        return $this->hasOne(Room::className(), ['id' => 'room_id']);
    }

}
