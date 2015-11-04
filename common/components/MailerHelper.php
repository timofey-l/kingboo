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

    /**
     * Отправляет письмо партнеру
     * 
     * @param $to
     * @param $sbj
     * @param @msg - array() каждый абзац - новый элемент массива
     * @param $type
     */
	public static function partnerEmail($to, $sbj, $msg, $locale, $type = 'Info') 
	{
        \Yii::$app->mailer->compose([
            'html' => 'partner' . $type . '-html',
            'text' => 'partner' . $type . '-text',
        ], [
            'message' => $msg,
            'locale' => $locale,
        ])->setFrom(\Yii::$app->params['email.from'])
        ->setTo($to)
        ->setSubject($sbj)
        ->send();		
	}
	
	/**
	 * Рассылка сообщений о приближении окончания тестового периода и о необходимости пополнить баланс
	 */ 
	public static function partnerBalanceInfo() {
	    $partners = \partner\models\PartnerUser::find()->all();
	    $now = new \DateTime();
	    $lkURL = \Yii::$app->params['partnerProtocol'] . '://' . \Yii::$app->params['partnerDomain'];
	    $formatter = clone \Yii::$app->formatter;
	    $report = '';
	    
	    foreach ($partners as $partner) {
	        // отсееваем тех, кто не подтвердился
	        if (!$partner->checked) {
	            continue;
	        }
	        
	        // если не истек тестовый период
	        if (!$partner->getDemoExpired()) {
	            $demo = new \DateTime($partner->demo_expire);
	            $n = $demo->diff($now);
	            if ($n->days < 5) {
	                $local = \common\models\Lang::findOne(['url' => $partner->lang])->local;
	                $formatter->locale = $local;
	                $sbj = \Yii::t('emails', 'King-Boo trial period wiil be expired soon', [], $local);
	                $msg = [];
                    $msg[] = \Yii::t('emails', 'The King-Boo system trial period is expired {0}', [$formatter->asDate($partner->demo_expire, 'long')], $local);
                    $msg[] = \Yii::t('emails', 'To continue work of the account, please, <a href="{0}">replanish your balance</a>.', [$lkURL], $local);
	                $report .= "{$partner->id}: {$partner->email}\n$sbj\n" . print_r($msg,true) . "\n";
                    self::partnerEmail($partner->email, $sbj, $msg, $local);
	            }
                continue;
            }
        
            // если тестовый период закончился
	        $local = \common\models\Lang::findOne(['url' => $partner->lang])->local;
	        $sbj = \Yii::t('emails', 'Replenish the balance in the King-Boo system', [], $local);
            $msg = [];
            if ($partner->isBlocked()) {
                $msg[] = \Yii::t('emails', 'The account is blocked. Your balance is {0}.', [$partner->billing->getBalanceString()], $local);
                $msg[] = \Yii::t('emails', 'To restore the account, please, <a href="{0}">replanish your balance</a>.', [$lkURL], $local);
            } elseif ($partner->billing->balance < 300) {
                $msg[] = \Yii::t('emails', 'Your balance is {0}.', [$partner->billing->getBalanceString()], $local);
                $msg[] = \Yii::t('emails', '<a href="{0}">Please, replanish your balance.</a>', [$lkURL], $local);
            }
            if ($msg) {
	            $report .= "{$partner->id}: {$partner->email}\n$sbj\n" . print_r($msg,true) . "\n";
                self::partnerEmail($partner->email, $sbj, $msg, $local);
            }
	    }
	    return $report;
	}

}