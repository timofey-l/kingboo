<?php
namespace partner\controllers;

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

    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => SupportMessage::find()->where([
                'author' => \Yii::$app->user->id,
                'parent_id' => null,
            ])->orderBy(['updated_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new SupportMessage();
        $model->scenario = 'insert';
        if ($model->load(\Yii::$app->request->post())) {
            $model->author = \Yii::$app->user->id;
            $model->parent_id = null;
            $model->unread = 0;
            $model->unread_admin = 1;

            if ($model->save()) {
                \Yii::$app->mailer->compose([
                    'html' => 'adminSupportEmail-html',
                    'text' => 'adminSupportEmail-text',
                ], [
                    'model' => $model,
                ])->setFrom($model->partner->email)
                    ->setTo(\Yii::$app->params['supportEmail'])
                    ->setSubject('New support thread #' . $model->id)
                    ->send();

                return $this->redirect(['thread', 'id' => $model->id]);
            }
        }

        $model->hash = \Yii::$app->security->generateRandomString(128);
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionThread($id) {
        $model = SupportMessage::findOne($id);
        if (!$model || $model->author != \Yii::$app->user->id || $model->parent_id) {
            throw new ForbiddenHttpException('Access forbidden');
        }

        $newMessage = new SupportMessage();
        if ($newMessage->load(\Yii::$app->request->post())) {
            $newMessage->author = \Yii::$app->user->id;
            $newMessage->parent_id = $model->id;
            $newMessage->unread = 0;
            $newMessage->unread_admin = 1;
            if ($newMessage->save()) {
                $model->touch('updated_at');
                return $this->redirect(['thread', 'id'=> $model->id, '#' => 'id'.$newMessage->id]);
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

        foreach ($answers as $answer) {
            $answer->unread = 0;
            $answer->save(false);
        }

        return $result;
    }

}