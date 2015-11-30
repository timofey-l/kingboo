<?php
return [
    'mainDomain' => 'www.king-boo.com',
    'mainProtocol' => 'http',
    'mainDomainShort' => 'king-boo.com',
    'partnerDomain' => 'partner.king-boo.com',
    'partnerProtocol' => 'https',
    'hotelsDomain' => 'king-boo.com',

    'allowPartnerDirectLogin' => false,
    'partnerDirectLoginTokenTimeout' => 5, // seconds

    'adminEmail' => ['admin@king-boo.com' => 'King-Boo Administrator', 'timofeylyzhenkov@gmail.com' => 'Timofey Lyzhenkov'],
    'supportEmail' => ['support@king-boo.com' => 'King-Boo Support', 'timofeylyzhenkov@gmail.com' => 'Timofey Lyzhenkov', 'perevod@it-translate.ru'],
    'email.from' => ['no-reply@king-boo.com' => 'King-Boo Booking System'],
    'email.manager' => ['client.consult@king-boo.com' => 'King-Boo Client Service'],

    'phones.manager' => '+7 499 707-82-84',

    'user.passwordResetTokenExpire' => 3600,
    'partner.demo' => 30, // продолжительность бесплатного периода в днях
    'partner.credit' => 100, // сумма в рублях, до которой партнер может уходить в кредит
    'partner.creditCurrency' => 'RUB',
    'partner.warning.daysBeforeDemoEnd' => 5, // за сколько дней до окончания демо-периода предупреждать
    'partner.warning.balanceShort' => 300, // предупреждать о низком балансе начиная с суммы

    'partner.unfreeze_limit_days' => 30, //days

    // если true, то при нахождении списания не входящего в диапазон дат для списания это списание будет удалено
    'partner.expenses.undefined.delete' => false,
    // отсылка письма при тех же условиях
    'partner.expenses.undefined.sendEmail' => true,

    /*'yandex' => [
        'demo' => true,
        'shopId' => '49653',
        'scid' => '527060',
        'shopPassword' => '85Xj2339XEv566S',
    ]  */
    'yandex' => [
        'demo' => false,
        'shopId' => '49653',
        'scid' => '35070',
        'shopPassword' => '85Xj2339XEv566S',
        'actionURL' => 'https://money.yandex.ru/eshop.xml',
        'demoActionURL' => 'https://demomoney.yandex.ru/eshop.xml',
    ],

    // кредит для партнеров (лимит отрицательного баланса) при котором не происходит блокировки.
    // При превышении этого лимита (если отель не заморожен) оплата будет сниматься, отель отображаться не будет.
];
