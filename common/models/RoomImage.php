<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_image}}".
 *
 * @property integer $id
 * @property integer $room_id
 * @property string $image
 */
class RoomImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id'], 'required'],
            [['room_id'], 'integer'],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['insert', 'update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rooms', 'ID'),
            'room_id' => Yii::t('rooms', 'Room ID'),
            'image' => Yii::t('rooms', 'Image'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'image' => [
                'class' => \mongosoft\file\UploadImageBehavior::className(),
                'attribute' => 'image',
                'instanceByName' => true,
                'scenarios' => ['insert', 'update', 'default'],
//                'placeholder' => '@app/web/images/noimage.png',
                'path' => '@common/uploads/room/{room_id}',
                'path' => '@common/uploads/room/{room_id}',
                'url' => '@web/uploads/room/{room_id}',
                'thumbs' => [
                    'thumb' => ['width' => 255, 'height' => 123, 'quality' => 100],
                    'preview' => ['width' => 600, 'height' => 352, 'quality' => 100],
                ],
            ],
        ];
    }

    public function fields() {
        return [
            'id',
            'image' => function ($model) {
                return $model->getUploadUrl('image');
            },
            'thumb' => function ($model) {
                return $model->getThumbUploadUrl('image');
            },
            'preview' => function ($model) {
                return $model->getThumbUploadUrl('image', 'preview');
            },
        ];
    }
    
    public function getRoom() {
        return $this->hasOne(Room::className(), ['id' => 'room_id']);
    }
}
