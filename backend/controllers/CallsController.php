<?php

namespace backend\controllers;

use common\models\CallsStats;
use common\models\DirectLoginTokens;
use partner\models\PartnerUser;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\User;

class CallsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'basicAuth' => [
                'class' => \yii\filters\auth\HttpBasicAuth::className(),
                'auth' => function ($username, $password) {
                    if ($username == "callsUser" && $password == '~^-{}7}(#t5}\8K') {
                        return new PartnerUser([
                            'username' => 'callsUser',
                        ]);
                    } elseif (!\yii::$app->user->isGuest) {
                        return \yii::$app->user;
                    }
                }
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'clearPage';
        $query = CallsStats::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoginTo($id)
    {
        $user = PartnerUser::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException;
        }

        $token = new DirectLoginTokens();
        $token->token = \Yii::$app->security->generateRandomString('32');
        $token->user_id = $id;
        $token->expire_date = (new \DateTime())
            ->add((new \DateInterval('PT' . \Yii::$app->params['partnerDirectLoginTokenTimeout'] . 'S')))
            ->format(\DateTime::ISO8601);
        if ($token->save()) {
            $url = \Yii::$app->params['partnerProtocol'] . "://" . \Yii::$app->params['partnerDomain'] . '/site/direct-login?token=' . $token->token;
            return $this->redirect($url);
        }


    }

}
