<?php

namespace common\models;

use partner\models\PartnerUser;
use Yii;

/**
 * This is the model class for table "{{%calls_stats}}".
 *
 * @property integer $id
 * @property string $date
 * @property string $company_name
 * @property string $email
 * @property string $phone
 * @property string $contact_person
 */
class CallsStats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%calls_stats}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['email'], 'unique', 'targetClass' => PartnerUser::className(), 'targetAttribute' => 'email'],
            [['company_name', 'email', 'phone'], 'required'],
            [['company_name', 'email', 'phone', 'contact_person'], 'string', 'max' => 255],
            [['date'], 'default', 'value' => date(\DateTime::ISO8601)],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('page', 'ID'),
            'date' => Yii::t('page', 'Date'),
            'company_name' => Yii::t('page', 'Название компании'),
            'email' => Yii::t('page', 'Email'),
            'phone' => Yii::t('page', 'Номер телефона'),
            'contact_person' => Yii::t('page', 'Контактное лицо'),
        ];
    }
}
