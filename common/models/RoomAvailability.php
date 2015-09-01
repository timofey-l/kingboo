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
            [['date', 'room_id', 'count', 'stop_sale'], 'required'],
            [['date'], 'safe'],
            [['room_id', 'count', 'stop_sale'], 'integer']
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
            'stop_sale' => Yii::t('rooms', 'Stop sale'),
        ];
    }

    public function getRoom() {
        return $this->hasOne(Room::className(), ['id' => 'room_id']);
    }

    public static function groupDelete($room, $startDate, $endDate) {
        $where = [ 'and',
            ['room_id' => $room->id],
            ['>=', 'date', $startDate],
            ['<=', 'date', $endDate],
        ];
        \Yii::$app->db->createCommand()->delete(self::tableName(), $where)->execute();
    }

    public static function groupInsert($room, $startDate, $endDate, $count, $stop_sale, $excludeDate = []) {
        $a = [];
        $date = \DateTime::createFromFormat('Y-m-d', $startDate);
        $to = \DateTime::createFromFormat('Y-m-d', $endDate); 
        if ($count !== false && $stop_sale !== false) {
            $fields = ['date', 'room_id', 'count', 'stop_sale'];
        } elseif ($count !== false && $stop_sale === false) {
            $fields = ['date', 'room_id', 'count'];
        } elseif ($count === false && $stop_sale !== false) {
            $fields = ['date', 'room_id', 'stop_sale'];
        } else {
            return;
        }
        while ($date <= $to) {
            if (in_array($date->format('Y-m-d'), $excludeDate)) {
                $date->modify('+1 day');
                continue;
            }
            if ($count !== false && $stop_sale !== false) {
                $a[] = [$date->format('Y-m-d'), $room->id, $count, $stop_sale];
            } elseif ($count !== false && $stop_sale === false) {
                $a[] = [$date->format('Y-m-d'), $room->id, $count];
            } elseif ($count === false && $stop_sale !== false) {
                $a[] = [$date->format('Y-m-d'), $room->id, $stop_sale];
            }
            $date->modify('+1 day');
        }
        if (!$a) {
            return;
        }
        \Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $a)->execute();
    }

    public static function groupUpdate($room, $startDate, $endDate, $count, $stop_sale) {
        if ($count !== false && $stop_sale !== false) {
            $a = ['count' => $count, 'stop_sale' => $stop_sale];
        } elseif ($count !== false && $stop_sale === false) {
            $a = ['count' => $count];
        } elseif ($count === false && $stop_sale !== false) {
            $a = ['stop_sale' => $stop_sale];
        } else {
            return;
        }
        $where = [ 'and',
            ['room_id' => $room->id],
            ['>=', 'date', $startDate],
            ['<=', 'date', $endDate],
        ];
        $params = [];

        \Yii::$app->db->createCommand()->update(self::tableName(), $a, $where, $params)->execute();
    }

}
