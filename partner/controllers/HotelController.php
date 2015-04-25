<?php

namespace partner\controllers;

use Yii;
use common\models\Hotel;
use partner\models\PartnerSearch;
use yii\filters\AccessControl;
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
                            return $hotel->partner_id == \Yii::$app->user->id;
                        }
                    ],
                    [
                        'actions' => ['index', 'create'],
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
     * Lists all Hotel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hotels = Yii::$app->user->identity->hotels;

        return $this->render('index', [
            'hotels' => $hotels,
        ]);
    }

    /**
     * Displays a single Hotel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [ 
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Hotel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hotel();
        $model->partner_id = Yii::$app->user->id;    
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
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
        $this->findModel($id)->delete();

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
    
}
