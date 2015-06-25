<?php

namespace partner\controllers;

use common\components\ListPriceRules;
use common\models\PriceRules;

class PriceRulesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate($type)
    {
        $model = ListPriceRules::getModel($type);

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
