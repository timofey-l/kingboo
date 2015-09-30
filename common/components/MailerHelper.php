<?php
namespace common\components;
 
use \Yii;
use yii\base\Component;
 
class MailerHelper extends Component {

	public static function adminEmail($sbj, $msg, $type = 'error') 
	{
        \Yii::$app->mailer->compose([
            'html' => $type . 'AdminEmail-html',
            'text' => $type . 'AdminEmail-text',
        ], [
            'message' => $msg,
        ])->setFrom(\Yii::$app->params['email.from'])
        ->setTo(\Yii::$app->params['adminEmail'])
        ->setSubject($sbj)
        ->send();		
	}

}