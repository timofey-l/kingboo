<?php

namespace backend\controllers;

use backend\models\LookupValue;
use common\models\Lang;
use Yii;
use backend\models\LookupField;
use backend\models\LookupFieldSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LookupsController implements the CRUD actions for LookupField model.
 */
class LookupsController extends Controller
{
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['admin']
//                    ]
//                ]
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all LookupField models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LookupFieldSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LookupField model.
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
     * Creates a new LookupField model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LookupField();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // model saved!
            $post_params = Yii::$app->request->post('LookupField');
            if (isset($post_params['values'])) {
                //$model->clearValues();
                $new_values_ids = [];
                $langs = array_keys($post_params['values']);
                unset($langs[0]);
                $langs = array_values($langs);
                if (count($langs) > 0) {
                    foreach($post_params['values'][$langs[0]] as $k => $v) {
                        // if value is new
                        if ($post_params['values']['id'][$k] == 0) {
                            $lookupValue = new LookupValue();
                            foreach ($langs as $lang) {
                                $lookupValue->{'value_'.$lang} = $post_params['values'][$lang][$k];
                            }
                            $lookupValue->lookup_field_id = $model->id;
                            $lookupValue->sort = $k;
                            $lookupValue->save();
                            $new_values_ids[] = $lookupValue->id;
                        } else {
                            // if value is present already
                            $lookupValue = LookupValue::findOne((integer)$post_params['values']['id'][$k]);
                            foreach ($langs as $lang) {
                                $lookupValue->{'value_'.$lang} = $post_params['values'][$lang][$k];
                            }
                            $lookupValue->lookup_field_id = $model->id;
                            $lookupValue->sort = $k;
                            $lookupValue->save();
                            $new_values_ids[] = $lookupValue->id;
                        }
                    }
                    // удаляем все которые не обновлены или не созданы
                    foreach ($model->values as $value) {
                        if (!in_array($value->id, $new_values_ids)) {
                            $value->delete();
                        }
                    }

                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'langs' => Lang::find()->all(),
            ]);
        }
    }

    /**
     * Updates an existing LookupField model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $post_params = Yii::$app->request->post('LookupField');
            if (isset($post_params['values'])) {
                //$model->clearValues();
                $new_values_ids = [];
                $langs = array_keys($post_params['values']);
                unset($langs[0]);
                $langs = array_values($langs);
                if (count($langs) > 0) {
                    foreach($post_params['values'][$langs[0]] as $k => $v) {
                        // if value is new
                        if ($post_params['values']['id'][$k] == 0) {
                            $lookupValue = new LookupValue();
                            foreach ($langs as $lang) {
                                $lookupValue->{'value_'.$lang} = $post_params['values'][$lang][$k];
                            }
                            $lookupValue->lookup_field_id = $id;
                            $lookupValue->sort = $k;
                            $lookupValue->save();
                            $new_values_ids[] = $lookupValue->id;
                        } else {
                            // if value is present already
                            $lookupValue = LookupValue::findOne((integer)$post_params['values']['id'][$k]);
                            foreach ($langs as $lang) {
                                $lookupValue->{'value_'.$lang} = $post_params['values'][$lang][$k];
                            }
                            $lookupValue->lookup_field_id = $id;
                            $lookupValue->sort = $k;
                            $lookupValue->save();
                            $new_values_ids[] = $lookupValue->id;
                        }
                    }
                    // удаляем все которые не обновлены или не созданы
                    foreach ($model->values as $value) {
                        if (!in_array($value->id, $new_values_ids)) {
                            $value->delete();
                        }
                    }

                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'langs' => Lang::find()->all(),
            ]);
        }
    }

    /**
     * Deletes an existing LookupField model.
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
     * Finds the LookupField model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LookupField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LookupField::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
