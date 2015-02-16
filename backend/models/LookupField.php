<?php

namespace backend\models;

use common\components\LookupBehaviour;
use Yii;

/**
 * This is the model class for table "{{%lookup_fields}}".
 *
 * @property integer $id
 * @property string $name
 */
class LookupField extends \yii\db\ActiveRecord
{
    public function clearValues()
    {
        if (count($this->values) > 0) {
            foreach ($this->values as $value) {
                /** @var $value LookupValue */
                $value->delete();
            }
        }
    }

    public function getValues()
    {
        return $this->hasMany(LookupValue::className(),['lookup_field_id' => 'id'])->orderBy('sort');
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lookup_fields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend_models', 'ID'),
            'name' => Yii::t('backend_models', 'Name'),
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            // Новая запись. Добавляем значения без проверки
            $lookupValue = new LookupValue();
        } else {

        }
    }
}
