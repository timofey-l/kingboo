<?php

/* @var $this yii\web\View */
/* @var $order common\models\Order */
/* @var $lang string */

if (!isset($lang)) {
    $lang = $order->lang;
}

?>
<div>
    <p><?= \Yii::t('mails_order', 'Hello, {name}!', ['name' => $order->contact_name . ' ' . $order->contact_surname], $local) ?></p>
    <p><?= \Yii::t('mails_order', 'You made a order on the site <a href="http://king-boo.com">king-boo.com</a>.', [], $local) ?></p>
    <p><?= \Yii::t('mails_order', 'Order number: {n}', ['n' => $order->number], $local) ?></p>
    <p><?= \Yii::t('mails_order', 'The rooms are now booked, but the booking will be canceled if payment is not received within 24 hours.', []) ?></p>
    <p><?= \Yii::t('mails_order', 'To make a payment, please click the link below <br/><a href="{url}">{url}</a>', ['url' => 'http://king-boo.com/payment/'.$order->payment_url], $local) ?></p>
    <p><?= \Yii::t('mails_order', 'Order details') ?>:</p>
    <p><?= $this->render('_order-html', ['order' => $order, 'lang' => $lang, 'local' => $local]) ?></p>
    <p></p>
    <p><?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $local) ?></p>
</div>


<div style="margin:0;padding:0;">
    <table cellpadding="0" cellspacing="0" width="100%"
           style="border-collapse:collapse;background-color:#F8F8F8;background-image:url(https://cache.mail.yandex.net/mails/7ad9b11acff785a38f41f1bd55a6c12b/paybylink.ru/invoice/assets/img/email_template/bg_texture.png);"
           align="center">
        <tbody>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" width="640" style="border-collapse:collapse;" align="center">
                    <tbody>
                    <tr>
                        <td height="60"></td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" width="640" style="border-collapse:collapse;">
                                <tbody>
                                <tr>
                                    <td width="5">
                                    </td>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" width="632"
                                               style="border-collapse:collapse;">
                                            <tbody>
                                            <tr>
                                                <td height="1" bgcolor="#E7E7E7" colspan="3">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="1" bgcolor="#E7E7E7">
                                                </td>
                                                <td bgcolor="#FFFFFF"
                                                    style="padding-top:30px;padding-right:0px;padding-bottom:20px;padding-left:0px;">
                                                    <table cellpadding="0" cellspacing="0" width="100%"
                                                           style="border-collapse:collapse;">
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="3" align="center">

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100">
                                                            </td>
                                                            <td width="420">
                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;">
                                                                    <?= \Yii::t('mails_order', 'Hello, {name}!', ['name' => $order->contact_name . ' ' . $order->contact_surname], $local) ?></p>

                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;">
                                                                    <?= \Yii::t('mails_order', 'You made a order on the site <a href="http://king-boo.com">king-boo.com</a>.', [], $local) ?>
                                                                </p>

                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;"><?= \Yii::t('mails_order', 'Order number: {n}', ['n' => $order->number], $local) ?></p>

                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;"><?= \Yii::t('mails_order', 'The rooms are now booked, but the booking will be canceled if payment is not received within 24 hours.', []) ?></p>

                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;"><?= \Yii::t('mails_order', 'To make a payment, please click the link below <br/><a href="{url}">{url}</a>', ['url' => 'http://king-boo.com/payment/' . $order->payment_url], $local) ?></p>

                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;"><?= \Yii::t('mails_order', 'Order details') ?>:</p>

                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;"><?= $this->render('_order-html', ['order' => $order, 'lang' => $lang, 'local' => $local]) ?></p>

                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;"></p>

                                                                <p style="color:#444444;font-family:Arial;font-size:14px;line-height:19px;margin-top:0;margin-bottom:8px;"><?= \Yii::t('mails_order', 'Best regards, team of king-boo.com', [], $local) ?></p>
                                                            </td>
                                                            <td width="100">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="630" colspan="3">
                                                                <p style="text-align:center;margin-top:45px;">
                                                                    <img width="630" height="3" alt=""
                                                                         src="https://cache.mail.yandex.net/mails/fb8cd42e57f1005d302b0f4effa6511b/paybylink.ru/invoice/assets/img/email_template/m_separator.png">
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" align="center">
                                                                <p>Выберите удобный для вас способ оплаты</p>

                                                                <p style="text-align:center;margin-top:20px;">
                                                                    <a href=""
                                                                       title="С помощью Яндекс.Денег" target="_blank"
                                                                       data-vdir-href=""
                                                                       data-orig-href=""
                                                                       class="daria-goto-anchor"><img width="299"
                                                                                                      height="50" alt=""
                                                                                                      src="https://cache.mail.yandex.net/mails/ea680d9f4eeb43d8521ededa2c5b0900/paybylink.ru/invoice/assets/img/email_template/yamoney.png"></a>
                                                                </p>

                                                                <p style="text-align:center;margin-top:20px;">
                                                                    <a href=""
                                                                       title="Банковской картой" target="_blank"
                                                                       data-vdir-href=""
                                                                       data-orig-href=""
                                                                       class="daria-goto-anchor"><img width="299"
                                                                                                      height="50" alt=""
                                                                                                      src="https://cache.mail.yandex.net/mails/a4835c4bc9efb0f0c239a30eb4bff5a1/paybylink.ru/invoice/assets/img/email_template/cards.png"></a>
                                                                </p>

                                                            </td>
                                                        </tr>


                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td width="1" bgcolor="#E7E7E7">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="3">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><img width="640" height="16" alt=""
                                                         src="https://cache.mail.yandex.net/mails/517a4ffa82abd9c3e67cd1bbf7663715/paybylink.ru/invoice/assets/img/email_template/shadow.png">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="50"></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>