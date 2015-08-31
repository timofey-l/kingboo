<?php
namespace common\components;

use yii\base\Component;

class YandexResponse extends Component
{
    var $performedDatetime;
    var $code = 0;
    var $invoiceId;
    var $shopId;
    var $message;

}