<?php

namespace common\models;

use Yii;
use common\components\ITDesignUploadImageBehavior;

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
                'class' => ITDesignUploadImageBehavior::className(),
                'attribute' => 'image',
                'instanceByName' => true,
                'scenarios' => ['insert', 'update', 'default'],
//                'placeholder' => '@app/web/images/noimage.png',
                'path' => '@common/uploads/hotel/{hotel_id}',
                'imageResize' => ['width' => 1200, 'height' => 1200, 'quality' => 100],
                'url' => '@web/uploads/hotel/{hotel_id}',
                'thumbs' => [
                    'thumb' => ['width' => 90, 'height' => 90, 'quality' => 100],
                    'preview' => ['width' => 160, 'height' => 90, 'quality' => 100],
                    'bigPreview' => ['width' => 700, 'height' => 412, 'quality' => 100],
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
            'id' => Yii::t('hotels', 'ID'),
            'hotel_id' => Yii::t('hotels', 'Hotel ID'),
            'image' => Yii::t('hotels', 'Image'),
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
