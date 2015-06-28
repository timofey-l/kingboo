<?php

namespace partner\controllers;

use common\components\ListPriceRules;
use common\models\PriceRules;
use common\models\Room;

class PriceRulesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $price_rules = PriceRules::find()
            ->where(['partner_id' => \Yii::$app->user->id])
            ->all();

        return $this->render('index', [
            'price_rules' => $price_rules,
        ]);
    }

    public function actionCreate($type)
    {
        /** @var PriceRules $model */
        $model = ListPriceRules::getModel($type);

        // пробуем получить данные из POST и сохранить правило
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            // проверяем условия
            $checkedValue = 'on';

            $valid = true;

            // диапазон ограничения даты бронирования
            if ($post['bookingRange'] === $checkedValue) {
                $valid = $valid && !(new \DateTime($model->dateFromB))->diff(new \DateTime($model->dateToB))->invert;
            } else {
                $model->dateFromB = null;
                $model->dateToB = null;
            }

            // диапазон ограничения дат проживания
            if ($post['livingRange'] === $checkedValue) {
                $valid = $valid && !(new \DateTime($model->dateFrom))->diff(new \DateTime($model->dateTo))->invert;
            } else {
                $model->dateFrom = null;
                $model->dateTo = null;
            }

            // минимальная сумма
            if ($post['minSum'] === $checkedValue) {
                $valid = $valid && $model->minSum > 0;
            } else {
                $model->minSum = null;
            }

            // максимальная сумма
            if ($post['maxSum'] === $checkedValue) {
                $valid = $valid && $model->maxSum > 0;
            } else {
                $model->minSum = null;
            }

            // код
            if ($post['checkCode'] === $checkedValue) {
                $valid = $valid && $model->code !== '' && $model->code != null && preg_match('/^[a-zA-Z0-9\-+_!]+$/', $model->code);
            } else {
                $model->code = null;
            }

            if ($valid && $model->save()) {
                // сохраняем ссылки на комнаты
                foreach ($post['rooms'] as $k => $v) {
                     $model->link('rooms', Room::findOne($v));
                }
                return $this->redirect(['price-rules/index']);
            }
        }

        // создаём новую скидку
        $rooms = Room::find()
            ->joinWith('hotel.partner')
            ->where(['partner_user.id' => \Yii::$app->user->id])
            ->all();

//        var_dump($rooms);

        return $this->render('create', [
            'model' => $model,
            'rooms' => $rooms,
        ]);
    }
}