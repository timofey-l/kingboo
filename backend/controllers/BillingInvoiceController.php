<?php

namespace backend\controllers;

use Yii;
use common\models\BillingInvoice;
use backend\models\BillingInvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use common\models\Currency;
use common\models\BillingIncome;
use yii\filters\AccessControl;

/**
 * BillingInvoiceController implements the CRUD actions for BillingInvoice model.
 */
class BillingInvoiceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
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
     * Lists all BillingInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BillingInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BillingInvoice model.
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
     * Creates a new BillingInvoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BillingInvoice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BillingInvoice model.
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
     * Deletes an existing BillingInvoice model.
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
     * Зачисление условных денег на счет партнера
     * Тестовые деньги помечаются как system = BillingInvoice::PAY_SYSTEM_TEST
     * Income для тестовых денег имеет pay_id = 0
     */
    public function actionAddTestMoney() {
        $invoice = new BillingInvoice();

        if ($invoice->load(Yii::$app->request->post())) {
            if (!$invoice->account) {
                throw new BadRequestHttpException('Wrong account id');
            }
            $invoice->currency_id = Currency::findOne(['code' => 'RUB'])->id;
            $invoice->system = BillingInvoice::PAY_SYSTEM_TEST; // yandex kassa
            $invoice->created_at = date('Y-m-d H:i:s');
            $invoice->payed = true;
            if (!$invoice->save()) {
                throw new BadRequestHttpException('Error while saving invoice: ' . print_r($income->errors, true));
            }

            $income = new BillingIncome();
            $income->sum = Currency::numberFormat($invoice->sum);
            $income->date = date('Y-m-d H:i:s');
            $income->currency_id = $invoice->currency_id;
            $income->account_id = $invoice->account_id;
            $income->invoice_id = $invoice->id;
            $income->pay_id = 0;
            if (!$income->save()) {
                $invoice->delete();
                throw new BadRequestHttpException('Error while saving income: ' . print_r($income->errors, true));
            }

            // Сигнал для системы сообщений
            $automaticSystemMessages = new \partner\components\PartnerAutomaticSystemMessages();
            $automaticSystemMessages->resetMessages($income->account->partner);

            return $this->redirect(['view', 'id' => $invoice->id]);
        } else {
            return $this->render('add-test-money', [
                'model' => $invoice,
            ]);
        }

    }

    /**
     * Finds the BillingInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BillingInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BillingInvoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
