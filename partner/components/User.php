<?php 
 
namespace partner\components;
 
class User extends \yii\web\User
{

    public function beforeLogin($identity, $cookieBased, $duration) {
        
        if (parent::beforeLogin($identity, $cookieBased, $duration)) {
            return $identity->checked;
        } else {
            return false;
        }
        
    }

}