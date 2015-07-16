<?php

namespace backend\controllers;

use backend\models\CreatePartnerForm;
use backend\models\PartnerUserSearch;
use partner\models\PartnerUser;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PartnerUserController implements the CRUD actions for PartnerUser model.
 */
class PartnerUserController extends Controller
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all PartnerUser models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PartnerUserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single PartnerUser model.
	 *
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
	 * Finds the PartnerUser model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return PartnerUser the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = PartnerUser::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * Creates a new PartnerUser model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new CreatePartnerForm();
		$model->scenario = 'create';

		if ($model->load(Yii::$app->request->post())) {
			if ($partner = $model->createPartner()) {
				return $this->redirect(['view', 'id' => $partner->id]);
			}
		}

		return $this->render('create', [
			'model' => $model,
		]);

	}

	/**
	 * Updates an existing PartnerUser model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = new CreatePartnerForm();
		$model->scenario = 'update';
		$partner = $this->findModel($id);

		if ($model->load(Yii::$app->request->post())) {
			$partner->username = $model->username;
			$partner->email = $model->email;
			$partner->shopId = $model->shopId;
			$partner->shopPassword = $model->shopPassword;
			$partner->scid = $model->scid;
            $partner->lang = $model->lang;

			if ($model->password != '') {
				$partner->setPassword($model->password);
			}
			if ($partner->validate() && $partner->save()) {
				return $this->redirect(['view', 'id' => $id]);
			}
		} else {
			$model->email = $partner->email;
			$model->username = $partner->username;
			$model->scid = $partner->scid;
			$model->shopId = $partner->shopId;
			$model->shopPassword = $partner->shopPassword;
            $model->lang = $partner->lang;

			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing PartnerUser model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}
}
