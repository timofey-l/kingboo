var bw_lang_vars = {
    ru: {
        datepicker: {
            closeText: 'Закрыть',
            prevText: '&#x3C;Пред',
            nextText: 'След&#x3E;',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                'Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            weekHeader: 'Нед',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        },
        messages: {
            'lang_dateFrom_label': "Дата заезда",
            'lang_dateTo_label': "Дата отъезда",
            'lang_adults_label': "Взрослые",
            'lang_children_label': 'Дети <br> 7-11 лет',
            'lang_kids_label': 'Дети <br> 0-6 лет',
            'lang_book_button': 'Забронировать',
            'lang_title': 'Онлайн бронирование'
        }
    },
    en: {
        datepicker: {
            closeText: 'Done',
            prevText: 'Prev',
            nextText: 'Next',
            currentText: 'Today',
            monthNames: ['January','February','March','April','May','June',
                'July','August','September','October','November','December'],
            monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            dayNamesMin: ['Su','Mo','Tu','We','Th','Fr','Sa'],
            weekHeader: 'Wk',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        },
        messages: {
            'lang_dateFrom_label': "Check-in date",
            'lang_dateTo_label': "Check-out date",
            'lang_adults_label': "Adults",
            'lang_children_label': 'Children <br> 7-11 y.o.',
            'lang_kids_label': 'Children <br> 0-6 y.o.',
            'lang_book_button': 'Book now',
            'lang_title': 'Online booking'
        }
    }
};
function initWidget(el, config) {
    if (typeof config == 'undefined') {
        config = {lang:{}};
    } else {
        if (typeof config.lang == 'undefined') config.lang = {};
    }

    var $ = jQuery;
    var $el = $(el);

    var $langs = $('<div class="bw__langs"></div>');

    for (var l in bw_lang_vars) {
        var $f = $('<span class="bw__langFlag" data-lang="' + l + '"></span>')
            .css({'background-image':'url(' + config.partnerUrl + 'flags/' + l + '.png)'})
            .click(function(e){
                changeWidgetLang(el, $(this).data('lang'));
            });
        $langs.append($f);
    }

    var $title = $('<div class="bw__titleContainer" data-bw-translate="lang_title"></div>');

    var $head = $('<div class="bw__headContainer"></div>').append($title).append($langs);

    var $dateFrom = $('<input readonly class="bw__date_input" type="text"/>');
    var $dateFromH = $('<input name="dateFrom" type="hidden">');

    var $dateTo = $('<input readonly class="bw__date_input" type="text"/>');
    var $dateToH = $('<input type="hidden" name="dateTo">');

    var $adults = $('<input readonly name="adults" class="bw__input_count" type="text" value="1"/>');
    var $children = $('<input readonly name="children" class="bw__input_count" type="text" value="0"/>');
    var $kids = $('<input readonly name="kids" class="bw__input_count" type="text" value="0"/>');

    var $dateFromLabel = $('<label data-bw-translate="lang_dateFrom_label"></label>');
    var $dateToLabel = $('<label data-bw-translate="lang_dateTo_label"></label>');
    var $adultsLabel = $('<label data-bw-translate="lang_adults_label"></label>');
    var $childrenLabel = $('<label data-bw-translate="lang_children_label"></label>');
    var $kidsLabel = $('<label data-bw-translate="lang_kids_label"></label>');

    var $datesContainer = $('<div class="bw__datesContainer"></div>')
        .append($('<div class="bw__dateFrom_container"></div>').append($dateFromLabel).append($dateFrom).append($dateFromH))
        .append($('<div class="bw__dateTo_container"></div>').append($dateToLabel).append($dateTo).append($dateToH));

    var $countsContainer = $('<div class="bw__countsContainer"></div>')
        .append($('<div class="bw__countContainer bw__adults"></div>').append($adultsLabel).append($adults))
        .append($('<div class="bw__countContainer bw__children"></div>').append($childrenLabel).append($children))
        .append($('<div class="bw__countContainer bw__kids"></div>').append($kidsLabel).append($kids));

    var $form  = $('<form name="' + el.id + '" action="'+config.url+'" method="GET"></form>')
        .append($datesContainer).append($countsContainer);
    var $submitButton = $('<button type="submit" class="bw__submitButton" data-bw-translate="lang_book_button"></button>').html(config.lang.submit || "Check");
    var $buttonContainer = $('<div class="bw__buttonContainer"></div>').append($submitButton);
    $form.append($buttonContainer);
    $el.append($head);
    $el.append($form);

    $el.css({display:'block'});
    var now = new Date();
    var dTo = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 7);

    $dateFromH.val($.datepicker.formatDate('yy-mm-dd', now));
    $dateToH.val($.datepicker.formatDate('yy-mm-dd', dTo));


    var $dateToDP;
    var $dateFromDP = $dateFrom.datepicker({
        changeMonth: true,
        minDate: now,
        maxDate: dTo,
        onClose: function(selDate){
            $dateToDP.datepicker('option', 'minDate', selDate);
            var dateStr = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));
            $dateFromH.val(dateStr);
        }
    });
    $dateFromDP.datepicker('setDate', now);

    $dateToDP = $dateTo.datepicker({
        changeMonth: true,
        minDate: now,
        onClose: function(selDate) {
            $dateFromDP.datepicker('option', 'maxDate', selDate);
            var dateStr = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));
            $dateToH.val(dateStr);
        }
    });
    $dateToDP.datepicker('setDate', dTo);

    $('#' + el.id + ' .bw__input_count').spinner({
        numberFormat: "n",
        min: 0,
        max: 10
    });
    $('#' + el.id + ' .bw__input_count[name=adults]').spinner({
        numberFormat: "n",
        min: 1,
        max: 10
    });

    // detect langs if not set in config
    if (typeof config.defaultLang == 'undefined') {
        var userL = navigator.languages[0].slice(0,2);
    } else {
        var userL = config.defaultLang;
    }

    if (typeof bw_lang_vars[userL] == 'undefined') {
        userL = 'en';
    }
    changeWidgetLang(el, userL);
}

function changeWidgetLang(el, lang) {
    if (typeof bw_lang_vars[lang] == 'undefined') {
        return false;
    }

    $('#' + el.id + ' [data-bw-translate]').each(function(i, element){
        $element = $(element);
        var message_id = $element.data('bw-translate');
        if (typeof bw_lang_vars[lang].messages[message_id] != 'undefined') {
            $element.html(bw_lang_vars[lang].messages[message_id]);
        }
    });

    // datepicker locales
    var locale = bw_lang_vars[lang].datepicker;
    $('#' + el.id + ' .bw__date_input').datepicker('option', locale);

    $("#" + el.id + ' .bw__langFlag.active').removeClass('active');
    $("#" + el.id + ' .bw__langFlag[data-lang=' + lang + ']').addClass('active');
    return true;
}

