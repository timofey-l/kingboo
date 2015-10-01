<?php
$this->registerJs("
    var ad = [
        {
            text: 'Профессиональный <b>сайт</b> с широкими возможностями для вашего отеля всего за <b>999 <i class=\"fa fa-rub\"></i>!</b>',
            img: '/img/robot-1.png',
            url: '/about#no-site',
        },
        {
            text: 'Система <b>онлайн бронирования</b> для вашего отеля с удобным управлением ценами, системой скидок и многим другим!',
            img: '/img/robot-2.png',
            url: '/about#have-site',
        },
        {
            text: 'Мы <b>не берем</b> процент с брони,<br />оплата за бронирование поступает<br />на <b>ваш счет без задержек!</b>',
            img: '/img/robot-3.png',
            url: '/about#have-site',
        },
    ];
    var adNum = 0;

    function preloadRoboImages() {
        for (var i in ad) {
            var el = $('<img src=\"'+ad[i].img+'\">');
        }
    }

    preloadRoboImages();

    function setHeaderBaloon() {
        var text = ad[adNum].text + '<a href=\"' + ad[adNum].url + '\" class=\"more\">Подробнее</a>';
        $('#top-baloon-text').html(text);
        if (ad[adNum].img.length > 0) {
            $('#logo-big-img').css('background-image', 'url(' + ad[adNum].img + ')');
        }
        if (adNum == ad.length - 1) {
            adNum = 0;
        } else {
            adNum++;
        }
        setTimeout(function() {
            setHeaderBaloon();
        },8000);
    }
",yii\web\View::POS_END);
$this->registerJs("
    setHeaderBaloon();
");

?>
<header>
    <div class="layer-olive-row"></div>
    <div class="left-round"></div>
    <div class="header-left-ellipses"></div>
    <div class="header-shine"></div>
    <div class="logo">
        <div class="logo-text"><span class="orange">King</span><span class="olive">Boo</span></div>
        <div class="text"><b>global </b>hotel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;website system <br>with
            <b>direct booking</b></div>
    </div>
    <div class="login-block"><a href="https://partner.king-boo.com/signup" class="button orange-bg">Регистрация</a><a href="https://partner.king-boo.com/signin" class="button green-bg">Вход</a>
    </div>
    <div class="header-top-text">Прямо сейчас самые <b>предприимчивые владельцы </b><br>отелей <b>регистрируются в
            сети King&ndash;Boo </b>и получают
    </div>
    <div class="baloon">
        <div class="top-baloon" id="top-baloon-text"></div>
    </div>
    <div class="logo-big-character" id="logo-big-img"></div>
    <a href="https://partner.king-boo.com/signup" class="join-now">Подключиться сейчас</a>

    <div class="header-bottom-text">

        Ваши клиенты <b>больше не ограничены </b>в способах оплаты <br>и могут забронировать номер <b>из любой точки
            мира! </b>
    </div>
    <?= $this->render('top-menu', []) ?>
</header>