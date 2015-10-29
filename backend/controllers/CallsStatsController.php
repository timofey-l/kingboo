<?php

namespace backend\controllers;

use common\components\MailerHelper;
use partner\models\PartnerUser;
use \Yii;
use common\models\CallsStats;
use yii\web\NotFoundHttpException;

class CallsStatsController extends \yii\web\Controller
{
    public function actionAdd($hash)
    {
        if ($hash != "VSbNbeIn1fPSONqrOTsW") {
            throw new NotFoundHttpException;
        }
        $this->layout = 'clearPage';

        $model = new CallsStats();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                // пробуем добавить партнера
                $user = new PartnerUser();
                $user->username = $model->email;
                $user->email = $model->email;
                $user->demo_expire = date('Y-m-d', time() + 86400 * \Yii::$app->params['partner.demo']);
                $password = \Yii::$app->security->generateRandomString(16);
                $user->setPassword($password);
                $user->generateAuthKey();
                if ($user->save()) {
                    $user->sendConfirmEmailWithPassword($password);
                    $model->date = date(\DateTime::ISO8601);
                    $model->save(false);
                    // send email to admins
                    MailerHelper::adminEmail('Регистрация нового пользователя через форму для колцентра', "<pre>".var_export($user->attributes, true)."</pre>", 'report');
                    \Yii::$app->session->setFlash('success', 'Пользователь был успешно создан!');
                    return $this->render('add', [
                        'model' => new CallsStats(),
                    ]);
                }
            } else {
                \Yii::$app->session->setFlash('danger', 'Проверьте данные в форме!');
            }
        }

        return $this->render('add', [
            'model' => $model,
        ]);
    }

}
