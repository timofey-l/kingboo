<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%support_messages}}".
 *
 * @property integer $id
 * @property integer $unread
 * @property integer $unread_admin
 * @property string $created_at
 * @property string $updated_at
 * @property string $text
 * @property integer $author
 * @property integer $parent_id
 * @property string $hash
 * @property string $title
 */
class SupportMessage extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%support_messages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unread', 'author', 'parent_id'], 'integer'],
            [['text'], 'required'],
            [['hash'], 'safe'],
            [['text', 'title'], 'string'],
            [['title', 'text'], 'required', 'on' => 'insert'],
        ];
    }

    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(),[
            'insert' => ['title', 'text'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('support', 'ID'),
            'unread' => Yii::t('support', 'Unread'),
            'unread_admin' => Yii::t('support', 'Unread admin'),
            'created_at' => Yii::t('support', 'Created At'),
            'updated_at' => Yii::t('support', 'Updated At'),
            'text' => Yii::t('support', 'Text'),
            'author' => Yii::t('support', 'Author'),
            'parent_id' => Yii::t('support', 'Parent ID'),
            'title' => Yii::t('support', 'Title'),
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert && !$this->hash) {
                $this->hash = \Yii::$app->security->generateRandomString(128);
            }
            return true;
        } else {
            return false;
        }
    }

    public function getNewMessages() {
        return static::find()
            ->where(['parent_id' => $this->id, 'unread' => 1])
            ->count();
    }

    public function getParent() {
        return $this->hasOne(static::className(), ['id' => 'parent_id']);
    }

    public function getTotalMessages() {
        return static::find()
            ->where(['parent_id'=>$this->id])
            ->count();
    }
}

