<?php
namespace common\components;

use backend\models\LookupValue;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class LookupBehaviour extends Behavior
{
    public $multiply = false;
    public $lookupFieldName = 'lookup';
    public $field = 'field';


    public function init()
    {
        parent::init();

        $this->{'get'.ucfirst($this->field)} = function () {
            if ($this->multiply) {
                LookupValue::findAll([]);
            }
        };

    }

}