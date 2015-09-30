<?php
namespace backend\controllers;

use common\models\SupportMessage;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class SupportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge($behaviors, [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ]);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SupportMessage::find()->where([
                'parent_id' => null,
            ]),
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionThread($id)
    {
        $model = SupportMessage::findOne($id);

        $newMessage = new SupportMessage();
        if ($newMessage->load(\Yii::$app->request->post())) {
            $newMessage->author = null;
            $newMessage->parent_id = $model->id;
            $newMessage->unread_admin = 0;
            $newMessage->unread = 1;
            if ($newMessage->save()) {
//                $model->touch();

                \Yii::$app->mailer->compose([
                    'html' => 'partnerSupportEmail-html',
                    'text' => 'partnerSupportEmail-text',
                ], [
                    'model' => $newMessage,
                ])->setFrom(\Yii::$app->params['supportEmail'])
                    ->setTo($model->partner->email)
                    ->setSubject(\Yii::t('support_backend', 'Support answer (thread #{n})', ['n' => $model->id]))
                    ->send();

                return $this->redirect(['thread', 'id' => $model->id, '#' => 'id' . $newMessage->id]);
            } else {
                throw new BadRequestHttpException();
            }
        }

        $newMessage->hash = \Yii::$app->security->generateRandomString(128);
        $answers = SupportMessage::find()
            ->where(['parent_id' => $model->id])
            ->orderBy(['created_at' => 'DESC'])
            ->all();

        $result = $this->render('thread', [
            'model' => $model,
            'answers' => $answers,
            'newMessage' => $newMessage,
        ]);

        return $result;
    }

    public function actionSetReaded($id)
    {
        $model = SupportMessage::findOne($id);
        if (!$model) {
            throw new ForbiddenHttpException('Model not found');
        }

        $model->unread_admin = 0;
        if ($model->save(false)) {
            return $this->redirect(['thread', 'id' => $model->parent_id ? $model->parent_id : $model->id]);
        }
    }

}