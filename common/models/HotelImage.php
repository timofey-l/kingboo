<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%hotel_image}}".
 *
 * @property integer $id
 * @property integer $hotel_id
 * @property string $image
 */
class HotelImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%hotel_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hotel_id'], 'required'],
            [['hotel_id'], 'integer'],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['insert', 'update']],
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
                'placeholder' => '@app/web/images/noimage.jpg',
                'path' => '@common/uploads/hotel/{hotel_id}',
                'url' => '@web/uploads/hotel/{hotel_id}',
                'thumbs' => [
                    'thumb' => ['width' => 90, 'height' => 90, 'quality' => 100],
                    'preview' => ['width' => 184, 'height' => 123, 'quality' => 100],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('hotel', 'ID'),
            'hotel_id' => Yii::t('hotel', 'Hotel ID'),
            'image' => Yii::t('hotel', 'Image'),
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
    
    public function getHotel() {
        return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
    }

}
