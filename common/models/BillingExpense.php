<?php

namespace common\models;

use common\components\MailerHelper;
use DateTime;
use Yii;

/**
 * This is the model class for table "{{%billing_expenses}}".
 *
 * @property integer $id
 * @property double $sum
 * @property integer $currency_id
 * @property string $date
 * @property integer $account_id
 * @property integer $service_id
 * @property string $comment
 */
class BillingExpense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%billing_expenses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sum', 'date', 'currency_id', 'account_id', 'service_id'], 'required'],
            [['sum'], 'number'],
            [['date'], 'safe'],
            [['currency_id', 'account_id', 'service_id'], 'integer'],
            [['comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing_expense', 'ID'),
            'sum' => Yii::t('billing_expense', 'Sum'),
            'currency_id' => Yii::t('billing_expense', 'Currency ID'),
            'date' => Yii::t('billing_expense', 'Date'),
            'account_id' => Yii::t('billing_expense', 'Account ID'),
            'service_id' => Yii::t('billing_expense', 'Service ID'),
            'comment' => Yii::t('billing_expense', 'Comment'),
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(BillingAccount::className(), ['id' => 'account_id']);
    }

    public function getService()
    {
        return $this->hasOne(BillingService::className(), ['id' => 'service_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // если добавление записи - пересчет balance в billing_account
        if ($insert) {
            $this->account->updateBalance();
        }
    }

    /**
     * Функция списания средств.
     * При запуске происходит проверка и при необходимости списание по всем активным услугам.
     *
     * @param integer|bool $accountServiceId Если задано, проверяется одна услуга
     * @param bool $returnLog Если true - возращает текст для дебага
     */
    public static function processExpenses($accountServiceId = false, $returnLog = true)
    {
        $out = "";
        echo "1";
        // полчаем все активные подключенные тарифы
        $query = BillingAccountServices::find()->where(['active' => 1]);
        if ($accountServiceId) {
            $out .= "Задан id услуги - $accountServiceId. Выборка ограничена этим элементом.\n";
            $query->andWhere(['id' => (int)$accountServiceId]);
        }
        $accountServices = $query->all();

        $n = 0;
        if ($accountServices) $n = count($accountServices);

        if ($n == 0) {
            $out .= "Выборка по заданным параметрам пуста. Завершение выполнения.\n";
            if ($returnLog) {
                return $out;
            } else {
                return true;
            }
        } else {
            $out .= "Количество найденых записей активных услуг для обработки: $n\n\n";
        }

        foreach($accountServices as $accountService) {
            /** @var BillingAccountServices $accountService */
            /** @var BillingAccount $account */
            $account = $accountService->account;
            /** @var BillingService $service */
            $service = $accountService->service;
            /** @var Hotel $hotel */
            $hotel = $accountService->hotel;

            $out .= "\n\n\n\n\n================================================================================\n";
            $out .= "Тарифный план: \n";
            $out .= "\tid:" . $accountService->id . "\n";

            if ($account === null || $service === null || $account->partner === null) {
                $out .= "\nНе заданы обязательные праметры. Переход к следующей записи.\n";
                continue;
            }

            $out .= "\tклиент: [id:{$account->partner->id}] {$account->partner->email}\n";
            $out .= "\tбаланс клиента: " . $account->currency->getFormatted($accountService->account->balance, 'code') . "\n";
            $out .= "\tотель: [id:" . $accountService->hotel_id . "]";
            if ($hotel) {
                $out .= " {$hotel->title_ru}";
            }
            $out .= "\n";
            $out .= "\tуслуга: [id: {$accountService->service_id}]";
            if ($service) {
                $out .= " {$service->name_ru}";
            }
            $out .= "\n";

            // по умолчанию списание разрешено
            $allowExpense = true;

            // получаем последнее списание
            /** @var BillingExpense $lastExpense */
            $lastExpense = BillingExpense::find()->where(['account_id' => $account->id, 'service_id' => $service->id])->orderBy(['date' => SORT_DESC])->one();
            if ($lastExpense === null) {
                //$out .= "Последнего списания не найдено.\n";
            } else {
                $out .= "Дата последнего списания: " . $lastExpense->date . "\n";
            }

            // Определяем тип списания
            // Месячную списываем раз в день
            // Годовую раз в год
            if ($service->monthly_cost > 0) {
                $out .= "Ежемесячное списание.\n";
                $checkLastPeriod = 24; // часа
                $sumToPay = round($service->monthly_cost * 12 / 365, 2);
            } else if ($service->yearly_cost > 0) {
                $out .= "Ежегодное списание.\n";
                $checkLastPeriod = 24 * 364; // часа ( 364 дня )
                $sumToPay = $service->yearly_cost;
            }

            // проверка по дате последнего списания
            if (!is_null($lastExpense)) {
                $dateLastExpense = new DateTime($lastExpense->date);
                $out .= "\nПроверка по дате поледнего списания.\n";
                $out .= "\tДата последнего списания: {$lastExpense->date}\n";
                $h = (new DateTime('now'))->diff($dateLastExpense)->h + (new DateTime('now'))->diff($dateLastExpense)->d * 24;
                $out .= "\tРазница в часах: {$h}\n";
                $out .= "\tДолжна быть более чем {$checkLastPeriod} ч.\n";
                if ($h < $checkLastPeriod) {
                    $allowExpense = false;
                    $out .= "Последнее списание проходило слишком мало времени назад.\nПереход к другой записи.";
                    continue;
                }
            }

            // если тестовый период еще не закончен - запрет на списание
            $out .= "\nПроверка на тестовый период.\n";
            $out .= "\tДата истечения тестового периода: {$account->partner->demo_expire}\n";
            $nowDate = date(\DateTime::ISO8601);
            $out .= "\tТекущая дата: {$nowDate}\n";
            if ((new DateTime($account->partner->demo_expire))->diff(new DateTime())->invert) {
                $allowExpense = false;
                $out .= "Тестовый период еще не завершен.\nПереход к следующей записи.\n";
                continue;
            }

            // проверяем баланс и если отрицательный - запрет на списывание
            $partnerCreditValue = \Yii::$app->params['partner.credit'];
            $partnerCreditCurrency = \Yii::$app->params['partner.creditCurrency'];
            $creditCurrency = Currency::findOne(['code' => $partnerCreditCurrency]);
            $allowedCreditValue = $account->currency->convertTo($partnerCreditValue, $partnerCreditCurrency);

            $account->updateBalance();
            $out .= "\nПроверка по флагу заморозки у отеля.\n";
            if ($hotel && $hotel->frozen) {
                $out .= "\tОтель заморожен. Переход к следующей записи.";
                continue;
            }


            // если разрешено списывать - делаем это
            if ($allowExpense) {
                $sum = $sumToPay;

                $newExpense = new BillingExpense;

                $newExpense->account_id = $account->id;
                $newExpense->sum = $sum;
                $newExpense->currency_id = $service->currency_id;
                $newExpense->date = date(DateTime::ISO8601);
                $newExpense->sum_currency_id = $service->currency_id;
                $newExpense->service_id = $service->id;
                $newExpense->comment = "Ежедневное списание средств по тарифу \"{$service->name_ru}\" (id: {$accountService->id})";
                if ($newExpense->save()) {
                    $sum = $newExpense->sum . ' ' . $service->currency->code;
                    $out .= "Запись списания успешно создана. id: {$newExpense->id}\n";
                } else {
                    // ошибка при сохранении списания
                    $errors = var_export($newExpense->errors, true);
                    $error_text = "";
                    $error_text .= "При списании средств произошла ошибка. Ошибки модели BillingExpense:\n";
                    $error_text .= $errors . "\n\n";

                    $error_text .= "Атрибуты модели BillingExpense:\n";
                    $error_text .= var_export($newExpense->attributes, true);

                    $out .= $error_text;
                    // отправка письма с ошибкой админам
                    MailerHelper::adminEmail('Ошибка при сохранении списания средств', "<pre>" . $error_text . "</pre>");
                }

            } else {
                $out .= "Списание средств не возможно.\n";
            }

            $out .= "\n";
        }
        return ($returnLog) ? $out : true;
    }

}
