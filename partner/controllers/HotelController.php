<?php

namespace partner\controllers;

use common\models\Widget;
use Yii;
use common\models\Hotel;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HotelController implements the CRUD actions for Hotel model.
 */
class HotelController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view', 'update', 'delete', 'rooms', 'images', 'facilities'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($action->id == 'delete') {
                                $id = \Yii::$app->request->post('id', false);
                            } else {
                                $id = \Yii::$app->request->get('id', false);
                            }
                            if (!$id) return false;
                            $hotel = \common\models\Hotel::findOne($id);
                            if (!$hotel) return false;
                            return $hotel->partner_id == \Yii::$app->user->id;
                        }
                    ],
                    [
                        'actions' => ['index', 'create', 'widgets', 'widget-create', 'delete-widget', 'update-widget'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays a single Hotel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $hotel = $this->findModel($id);
        return $this->render('view', [
            'model' => $hotel,
            'rooms' => $hotel->rooms,
        ]);
    }

    /**
     * Creates a new Hotel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Hotel();
        $model->partner_id = Yii::$app->user->id;    
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'hotel' => Hotel::findOne($id),
            ]);
        }
    }

    /**
     * Updates an existing Hotel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id); 

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'hotel' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Hotel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
    * Выводит номера отеля
    * 
    * @param mixed $id
    * @return string
    */
    public function actionRooms($id)
    {
        return $this->render('rooms', [ 
            'model' => $this->findModel($id),
        ]);
    }

    /**
    * Редактирует фотографии отеля
    * 
    * @param mixed $id
    * @return string
    */
    public function actionImages($id)
    {
        return $this->render('images', [ 
            'model' => $this->findModel($id),
        ]);
    }

    /**
    * Редактирует особенности отеля
    * 
    * @param mixed $id
    * @return string
    */
    public function actionFacilities($id)
    {
        $hotel = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {//обработка входящих данных
            //удаляем существующие связи
            $old = $hotel->facilities;
            foreach ($old as $f) {
                $hotel->unlink('facilities', $f, true);
            }
            
            //добавляем новые связи
            $new = Yii::$app->request->post('facilities',[]);
            foreach ($new as $k=>$id) {
                if (($f = \common\models\HotelFacilities::findOne($id)) !== null) {
                    $hotel->link('facilities',$f);
                }
            }
            
            return $this->redirect(['view', 'id' => $hotel->id]);
            
        } else {//Вывод формы
            $groups = \common\components\ListFacilitiesType::options();
            $checked = $hotel->facilityArray();
            $facilities = [];
            foreach ($groups as $k=>$gr) {
                $facilities[$k] = \common\models\HotelFacilities::options(['group_id' => $k],$checked);
            }
            
            return $this->render('facilities', [ 
                'model' => $this->findModel($id),
                'groups' => $groups,
                'facilities' => $facilities,
            ]);
        }
    }
    
    /**
     * Finds the Hotel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hotel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hotel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all Widget models.
     * @param $id
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionWidgets($id)
    {
        if (!$hotel = Hotel::findOne($id)) {
            throw new BadRequestHttpException();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Widget::find()
                ->where(['hotel_id' => $id])
        ]);

        return $this->render('widgets', [
            'dataProvider' => $dataProvider,
            'hotel_id' => $id,
            'hotel' => $hotel,
        ]);
    }

    /**
     * Creates a new Widget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionWidgetCreate($id)
    {
        $hotel = Hotel::findOne($id);
        if (!$hotel) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = new Widget();
        $model->params = Json::encode(Widget::$defaultParams);
        if ($model->load(Yii::$app->request->post())) {
            $model->params = Json::encode(ArrayHelper::merge(Widget::$defaultParams, Yii::$app->request->post('Widget')['params']));
            $model->hash_code = \Yii::$app->getSecurity()->generateRandomString();
            $model->hotel_id = $id;
            if ($model->save())
                return $this->redirect(['widgets', 'id' => $id]);
            else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create-widget', [
                'model' => $model,
                'hotel' => $hotel,
            ]);
        }
    }

    /**
     * Deletes an existing Widget model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteWidget($id)
    {
        $model = Widget::findOne($id);
        $hotel_id = $model->hotel_id;
        $model->delete();

        return $this->redirect(['widgets', 'id' => $hotel_id]);
    }

    /**
     * Updates an existing Widget model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdateWidget($id)
    {
        $model = Widget::findOne($id);
        $hotel = Hotel::findOne($model->hotel_id);
        if (!$model || !$hotel) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->params = Json::encode(ArrayHelper::merge(Widget::$defaultParams, Yii::$app->request->post('Widget')['params']));
            if ($model->save()) {
                return $this->redirect(['widgets', 'id' => $model->hotel_id]);
            }
        }

        return $this->render('update-widget', [
            'model' => $model,
            'hotel' => $hotel,
        ]);

    }
}
