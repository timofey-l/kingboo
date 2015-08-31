<?php
namespace common\components;

use common\models\Lang;
use yii\helpers\ArrayHelper;

/**
 * Class YandexHelper
 *
 * Весь функционал связанный с ЯндексКассой
 *
 * @package common\components
 */
class YandexHelper
{
    const PAY_TYPE_PC = 0; // Оплата из кошелька в Яндекс.Деньгах.
    const PAY_TYPE_AC = 1; // Оплата с произвольной банковской карты.
    const PAY_TYPE_MC = 2; // Платеж со счета мобильного телефона.
    const PAY_TYPE_GP = 3; // Оплата наличными через кассы и терминалы.
    const PAY_TYPE_WM = 4; // Оплата из кошелька в системе WebMoney.
    const PAY_TYPE_SB = 5; // Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн.
    const PAY_TYPE_MP = 6; // Оплата через мобильный терминал (mPOS).
    const PAY_TYPE_AB = 7; // Оплата через Альфа-Клик.
    const PAY_TYPE_МА = 8; // Оплата через MasterPass.
    const PAY_TYPE_PB = 9; // Оплата через Промсвязьбанк.

    public static $pay_type = [
        [
            'code' => 'PC',
            'title_ru' => 'Оплата из кошелька в Яндекс.Деньгах',
            'title_en' => 'Payment from Yandex.Money purse',
        ],
        [
            'code' => 'AC',
            'title_ru' => 'Оплата с произвольной банковской карты',
            'title_en' => 'Payment by any bank card',
        ],
        [
            'code' => 'MC',
            'title_ru' => 'Платеж со счета мобильного телефона',
            'title_en' => 'Payment from the mobile phone account',
        ],
        [
            'code' => 'GP',
            'title_ru' => 'Оплата наличными через кассы и терминалы',
            'title_en' => ' Cash payments via cash offices and terminals',
        ],
        [
            'code' => 'WM',
            'title_ru' => 'Оплата из кошелька в системе WebMoney',
            'title_en' => 'Payment from  WebMoney system purse',
        ],
        [
            'code' => 'SB',
            'title_ru' => 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн',
            'title_en' => 'Sberbank payments: payment via SMS or Sberbank Online',
        ],
        [
            'code' => 'MP',
            'title_ru' => 'Оплата через мобильный терминал (mPOS)',
            'title_en' => 'Payment via mobile terminal (mPOS)',
        ],
        [
            'code' => 'AB',
            'title_ru' => 'Оплата через Альфа-Клик',
            'title_en' => 'Payment via Alfa-Click',
        ],
        [
            'code' => 'MA',
            'title_ru' => 'Оплата через MasterPass',
            'title_en' => 'Payment via MasterPass',
        ],
        [
            'code' => 'PB',
            'title_ru' => 'Оплата через Промсвязьбанк',
            'title_en' => 'Payment via Promsvyazbank',
        ],

    ];

    /**
     * Получение id типа оплаты по коду для записи в базу данных
     *
     * @param null $code
     * @return int|null
     */
    public static function getIdByCode($code = null)
    {
        foreach (static::$pay_type as $id => $pay_type) {
            if ($code == $pay_type['code']) {
                return $id;
            }
        }
        return null;
    }

    /**
     * Получение заголовка по id типа оплаты
     *
     * @param integer $id
     * @param bool $lang
     * @return mixed
     */
    public static function getPayTypeTitle($id, $lang = false)
    {
        if ($lang == false) {
            $lang = Lang::$current->url;
        }

        if (isset(static::$pay_type[$id])) {
            return static::$pay_type[$id]['title_' . $lang];
        }
    }

    public static function checkMd5($action, $params, $partner)
    {
        $hashArray = [];
        $hashArray[] = $action;
        $hashArray[] = ArrayHelper::getValue($params, 'orderSumAmount', '');
        $hashArray[] = ArrayHelper::getValue($params, 'orderSumCurrencyPaycash', '');
        $hashArray[] = ArrayHelper::getValue($params, 'orderSumBankPaycash', '');
        $hashArray[] = ArrayHelper::getValue($params, 'shopId', '');
        $hashArray[] = ArrayHelper::getValue($params, 'invoiceId', '');
        $hashArray[] = ArrayHelper::getValue($params, 'customerNumber', '');
        $hashArray[] = $partner->shopPassword;
        return strtolower(md5(implode(';', $hashArray))) == strtolower($params['md5']);
    }

    public static function checkMd5Common($action, $params, $confParams)
    {
        $hashArray = [];
        $hashArray[] = $action;
        $hashArray[] = ArrayHelper::getValue($params, 'orderSumAmount', '');
        $hashArray[] = ArrayHelper::getValue($params, 'orderSumCurrencyPaycash', '');
        $hashArray[] = ArrayHelper::getValue($params, 'orderSumBankPaycash', '');
        $hashArray[] = ArrayHelper::getValue($params, 'shopId', '');
        $hashArray[] = ArrayHelper::getValue($params, 'invoiceId', '');
        $hashArray[] = ArrayHelper::getValue($params, 'customerNumber', '');
        $hashArray[] = ArrayHelper::getValue($confParams,'shopPassword', '');
        return strtolower(md5(implode(';', $hashArray))) == strtolower(ArrayHelper::getValue($params,'md5', ''));
    }

    public static function getForm($params = false)
    {
        $url = \Yii::$app->params['yandex']['demo'] ? "https://demomoney.yandex.ru/eshop.xml" : "https://money.yandex.ru/eshop.xml";
        $form = "<form action=\"{$url}\" method='post'>";
        foreach ($params as $name => $value) {
            $input = "<input name='{$name}' value='{$value}' type='hidden'/>";
            $form .= $input;
        }
        $form .= "</form>";

        return $form;
    }
}