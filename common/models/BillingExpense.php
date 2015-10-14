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
            $lastExpense = BillingExpense::find()->where(['account_id' => $account->id, 'service_id' => $accountService->id])->orderBy(['date' => SORT_DESC])->one();
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
                $checkLastPeriod = 23; // часа
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
                $newExpense->service_id = $accountService->id;
                $newExpense->comment = "Ежедневное списание средств по услуге \"{$service->name_ru}\" (id: {$accountService->id})";
                if ($newExpense->save()) {
                    $sum = $newExpense->sum . ' ' . $service->currency->code;
                    $out .= "Запись списания успешно создана. id: {$newExpense->id}\n";

                    // Сигнал для системы сообщений
//                    $automaticSystemMessages = new \partner\components\PartnerAutomaticSystemMessages();
//                    $automaticSystemMessages->resetMessages($account->partner);
//                    unset($automaticSystemMessages);
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

    /**
     * Новый метод списания денег, который учитвыет ранее неучтенные даты и даты с замороженным состоянием
     *
     * @param bool|false $acconutServiceId
     * @param bool|false $returnLog
     */
    public static function processExpensesNew($acconutServiceId = false, $returnLog = true)
    {
        $log = "Запуск процедуры списания средств\n";

        $query = BillingAccountServices::find();
        $query->where(['active' => true]);
        if ($acconutServiceId) {
            $log .= "Задан id услуги (id: $acconutServiceId). Выборка будет ограничена только этим элементом!\n";
            $query->andWhere(['id' => $acconutServiceId]);
        }
        $accountServices = $query->all();

        if (!$accountServices) {
            $log .= "Поиск вернул пустой результат.\nЗавршение работы процедуры списания.";
            return $returnLog ? $log : false;
        }

        if (!$acconutServiceId) {
            $log .= "Найдено записей для обработки: " . count($accountServices) . "\n";
        }

        foreach ($accountServices as $i => $accountService) {
            $log .= "\n===================================================\n";
            $log .= "Обработка записи id: {$accountService->id}\n";
            $log .= "---------------------------------------------------\n";

            /** @var BillingService $service */
            $service = $accountService->service;
            /** @var BillingAccount $account */
            $account = $accountService->account;
            /** @var Hotel $hotel */
            $hotel = $accountService->hotel;
            if (!$service || !$account || !$account->getPartner()->one()) {
                $log .= "Ошибка определения услуги или связи с аккаунтом.\nПереход к следующей записи.\n";
                MailerHelper::adminEmail('Ошибка с услугой. billing_account_serivices. id=' . $accountService->id, $log, 'error');
                continue;
            }

            $log .= "\tклиент: [id:{$account->partner->id}] {$account->partner->email}\n";
            $log .= "\tбаланс клиента: " . $account->currency->getFormatted($accountService->account->balance, 'code') . "\n";
            $log .= "\tотель: [id:" . $accountService->hotel_id . "]";
            if ($hotel) {
                $log .= " {$hotel->title_ru}";
            }
            $log .= "\n";
            $log .= "\tуслуга: [id: {$accountService->service_id}]";
            if ($service) {
                $log .= " {$service->name_ru}";
            }
            $log .= "\n";


            /** @var BillingAccountServices $accountService */
            $sum = 0;

            if ($service->monthly_cost > 0 && !$hotel) {
                $log .= "Ошибка определения отеля.\nПереход к следующей записи.\n";
                continue;
            }

            $type = 'm'; // m - месячное списание, y - годичное
            if ($service->yearly_cost) {
                $type = "y";
            }

            // проверка завершения демо периода
            $log .= "Дата истечения тестового периода: {$account->partner->demo_expire}\n";
            $nowDate = date(\DateTime::ISO8601);
            $log .= "Текущая дата: {$nowDate}\n";
            if ((new DateTime($account->partner->demo_expire))->diff(new DateTime())->invert) {
                $log .= "Тестовый период еще не завершен.\nПереход к следующей записи.\n";
                continue;
            }

            switch ($type) {
                case "m":
                    $log .= "Услуга с ежемесячной ценой. Списание каждый день.\n";
                    $sum = round($service->monthly_cost * 12 / 365, 2);
                    $log .= "Сумма для списния в сутки: " . $service->currency->getFormatted($sum, 'code') . "\n";

                    // создание календаря оплаты
                    // На каждый день должно быть определена сумма или флаг заморозки
                    $calendar_array = [];
                    $dateStart = new \DateTime($account->partner->demo_expire);
                    $dateEnd = new \DateTime();
                    $log .= "Строим календарь с " . $dateStart->format('Y-m-d') . ' по ' . $dateEnd->format('Y-m-d') . "\n";
                    //$dateEnd->modify("+1 day");
                    $interval = new \DateInterval("P1D");
                    $dateRange = new \DatePeriod($dateStart, $interval, $dateEnd);
                    foreach ($dateRange as $date) {
                        $calendar_array[$date->format('Y-m-d')] = null;
                    }

                    // получение всех списаний и занесение их в календарь
                    $expenses = BillingExpense::find()
                        ->where(['service_id' => $accountService->id])
                        ->orderBy(['date' => SORT_ASC])
                        ->all();
                    if ($expenses) {
                        foreach ($expenses as $expense) {
                            $expDate = (new DateTime($expense->date))->format('Y-m-d');

                            if (array_key_exists($expDate, $calendar_array)) {
                                $calendar_array[$expDate] = $expense;
                            } else {
                                $log .= "Найдено списание с датой не входящей в диапазон действия услуги!  [{$dateEnd->format('Y-m-d')}] [id:{$expense->id}]\n";
                                MailerHelper::adminEmail( "Найдено списание с датой не входящей в диапазон действия услуги!  [{$dateEnd->format('Y-m-d')}] [id:{$expense->id}]", $log, 'error');
                            }
                        }
                    }

                    // проверяем календарь
                    // если значение на какой-то день равно null - создаем списание
                    $log .= "Проверка календаря оплаты...\n";
                    foreach ($calendar_array as $date => $exp) {
                        if (is_null($exp)) {
                            $log .= "На дату $date отсутствует списание.\n";

                            $newExp = new BillingExpense();
                            $newExp->account_id = $account->id;
                            $newExp->service_id = $accountService->id;
                            $newExp->sum = $sum;
                            $newExp->date = $date;
                            $newExp->comment = "Ежедневное списание средств по услуге \"{$service->name_ru}\" (id: {$accountService->id})";
                            $newExp->currency_id = $service->currency_id;
                            $newExp->sum_currency_id = $service->currency_id;
                            $newExp->frozen = false;
                            $newExp->created_at = date(\DateTime::ISO8601);

                            //проверка на факт заморозки
                            if ($hotel->frozen) {
                                // попадает ли дата в диапазон заморозки
                                if ((new DateTime($hotel->freeze_time)) <= (new DateTime($date))) {
                                    $log .= "Дата попадает в диапазон заморозки отеля.\nСтавим отметку о заморозке и обнуляем сумму.";
                                    $newExp->sum = 0;
                                    $newExp->frozen = true;
                                }
                            }

                            if ($newExp->save()) {
                                $log .= "Запись списания успешно создана. [id: {$newExp->id}]\n";
                            } else {
                                $log .= "Ошибка при создании записи списания.\n";
                                $log .= "Атрибуты модели:\n";
                                $log .= var_export($newExp->attributes, true) . "\n";
                                $log .= "Ошибки модели:\n";
                                $log .= var_export($newExp->errors, true) . "\n";
                                MailerHelper::adminEmail('Ошибка при создании записи списания.', $log, 'error');
                            }
                            $log .= "\n";
                        }
                    }
                    $log .= "Проверка календаря завершена.\nПереход к другому тарифу.\n";

                    break;

                case "y":
                    $log .= "Услуга с ежегодной ценой. Списание каждый день.\n";
                    $sum = round($service->yearly_cost, 2);
                    $log .= "Сумма для списния в год: " . $service->currency->getFormatted($sum, 'code') . "\n";

                    // создание календаря оплаты
                    $calendar_array = [];
                    $dateStart = new \DateTime($accountService->add_date);
                    $dateEnd = (new \DateTime($accountService->add_date))->modify('+1 year');
                    $dateNow = new DateTime();
                    while ($dateStart <= $dateNow && $dateNow < $dateEnd) {
                        $calendar_array[$dateStart->format('Y-m-d') . ';' . $dateEnd->format('Y-m-d')] = null;
                        $dateStart->modify('+1 year');
                        $dateEnd->modify('+1 year');
                    }

                    // получение всех списаний и занесение их в календарь
                    $expenses = BillingExpense::find()
                        ->where(['service_id' => $accountService->id])
                        ->orderBy(['date' => SORT_ASC])
                        ->all();
                    if ($expenses) {
                        foreach ($expenses as $expense) {
                            $expDate = (new DateTime($expense->date))->format('Y-m-d');

                            // Проверяем попадание даты отметки списания в какойто из периодов в календаре
                            foreach ($calendar_array as $date => $val) {
                                $checkDateStart = new DateTime(explode(';', $date)[0]);
                                $checkDateEnd = new DateTime(explode(';', $date)[1]);
                                if ($checkDateStart <= $expDate && $expDate < $checkDateEnd) {
                                    $calendar_array[$date] = $expense;
                                }
                            }
                        }
                    }

                    // проверяем календарь
                    // если значение на какой-то день равно null - создаем списание
                    foreach ($calendar_array as $date => $exp) {
                        if (is_null($exp)) {
                            $newDateStart = new DateTime(explode(';', $date)[0]);
                            $newDateEnd = new DateTime(explode(';', $date)[1]);
                            $log .= "На диапазон [{$newDateStart->format('Y/m/d')} - {$newDateEnd->format('Y/m/d')}) отсутствует списание.\n";

                            $newExp = new BillingExpense();
                            $newExp->account_id = $account->id;
                            $newExp->service_id = $accountService->id;
                            $newExp->sum = $sum;
                            $newExp->date = $date;
                            $newExp->comment = "Ежегодное списание средств по услуге \"{$service->name_ru}\" (id: {$accountService->id}) за период [{$newDateStart->format('Y/m/d')} - {$newDateEnd->format('Y/m/d')})";
                            $newExp->currency_id = $service->currency_id;
                            $newExp->sum_currency_id = $service->currency_id;
                            $newExp->frozen = false;
                            $newExp->created_at = date(\DateTime::ISO8601);

                            // TODO: определить влияние флага заморозки на ежегодный списания за домен

                            if ($newExp->save()) {
                                $log .= "Запись списания успешно создана. [id: {$newExp->id}]\n";
                            } else {
                                $log .= "Ошибка при создании записи списания.\n";
                                $log .= "Атрибуты модели:\n";
                                $log .= var_export($newExp->attributes, true) . "\n";
                                $log .= "Ошибки модели:\n";
                                $log .= var_export($newExp->errors, true) . "\n";
                                MailerHelper::adminEmail('Ошибка при создании записи списания.', $log, 'error');
                            }
                            $log .= "\n";
                        }
                    }
                    break;
            }
        }
        $log .= "Процедура списания денежных средств завершена.\n";
        return $returnLog ? $log : true;
    }

}
