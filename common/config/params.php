<?php
return [
    'mainDomain' => 'www.king-boo.com',
    'mainProtocol' => 'http',
    'mainDomainShort' => 'king-boo.com',
    'partnerDomain' => 'partner.king-boo.com',
    'partnerProtocol' => 'https',
    'hotelsDomain' => 'king-boo.com',

    'adminEmail' => ['admin@king-boo.com' => 'King-Boo Administrator', 'timofeylyzhenkov@gmail.com' => 'Timofey Lyzhenkov'],
    'supportEmail' => ['support@king-boo.com' => 'King-Boo Support', 'timofeylyzhenkov@gmail.com' => 'Timofey Lyzhenkov', 'perevod@it-translate.ru'],
    'email.from' => ['no-reply@king-boo.com' => 'King-Boo Booking System'],

    'user.passwordResetTokenExpire' => 3600,
    'partner.demo' => 30, // продолжительность бесплатного периода в днях
    'partner.credit' => 100, // сумма в рублях, до которой партнер может уходить в кредит

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
    ]
];
