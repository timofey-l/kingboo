var LANGS = {};

LANGS.ru = {
    room_add: "Добавить номер",
    room_title_ru: "Название RU",
    room_title_en: "Название EN",
    room_description_ru: "Описание RU",
    room_description_en: "Описание EN",

    delete_confirm: 'Вы уверены, что хотите удалить номер?\n' +
    'В случае продолжения номер будет удален безвозвратно!\n' +
    'Если Вы понимаете что делаете, введите "delete" для выполнения операции:',

    room_table_head_title: "Название",
    room_table_head_adults: "Взрослых",
    room_table_head_children: "Детей",
    room_table_head_total: "Всего",
    room_table_head_price_type : "Тип стоимости",
    
    room_table_head_list: "Список номеров",
    room_table_head_delete: "Удалить",
    room_table_head_update: "Редактировать",
    room_table_head_money: "Цены",
    room_table_head_timetable: "Количество и доступность номеров",

    room_adults_title: "Взрослых",
    room_children_title: "Детей",
    room_total_title: "Всего",
    room_price_type_title : "Тип стоимости",

    room_list_empty: "Номера отсутствуют!",

    room_list_title: "Список номеров",
    room_edit_title: "Редактирование номера",
    room_save : "Сохранить",
    room_null_text: "Такого номера не найдено.",
    room_cancel : "Отмена",
    
    room_price_head : 'Управление ценами на номер',
    room_price_date_range : "Период",
    room_price_show : "Показать",
    
    price_table_col_1_title : "Варианты",
    price_table_col_1_description: "Количество взрослых + количество детей (7-11 лет) + количество детей (0-6 лет)",
    price_table_fixed_price: "Фикс. цена",
};

LANGS.en = {
    room_add: "Add room",
    room_title_ru: "Title RU",
    room_title_en: "Title EN",
    room_description_ru: "Description RU",
    room_description_en: "Description EN",

    delete_confirm: 'Are you sure want to delete this room?\n' +
    'If you know that you doing, please type word "delete", to continue operation:',

    room_table_head_title: "Title",
    room_table_head_adults: "Adults",
    room_table_head_children: "Children",
    room_table_head_price_type : "Price type",
    room_table_head_total: "Title",
    
    room_table_head_list: "Room list",
    room_table_head_delete: "Delete",
    room_table_head_update: "Edit",
    room_table_head_money: "Prices",
    room_table_head_timetable: "Количество и доступность номеров",

    room_adults_title: "Adults",
    room_children_title: "Children",
    room_total_title: "Total",
    room_price_type_title : "Price type",

    room_list_empty: "Rooms are absent!",

    room_list_title: "Rooms list",
    room_edit_title: "Edit room",
    room_save : "Save",
    room_null_text: "Cannot find room.",
    room_cancel : "Cancel",
    
    room_price_head : 'Room prices manage',
    room_price_date_range : "Period",
    room_price_show : "Show",

    price_table_col_1_title : "Types",
    price_table_col_1_description: "Adults + kids (7-11 y.o.) + children (0-6 y.o)",
    price_table_fixed_price: "Fixed price",
};

function t(v, l) {
    if (typeof l == 'undefined' && typeof window.LANG !== 'undefined') {
        l = window.LANG;
    }

    if (typeof LANGS[l][v] != 'undefined') {
        return LANGS[l][v]
    } else {
        return v;
    }
}

var locale = {
    en: { },
    ru: { }
};

locale.en.date = {
    applyLabel: 'Submit',
    cancelLabel: 'Cancel',
    fromLabel: 'From',
    toLabel: 'To',
    customRangeLabel: 'Custom',
    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    firstDay: 0
}

locale.en.dateFormat = 'MM/DD/YYYY';

locale.ru.date = {
    applyLabel: 'Вставить',
    cancelLabel: 'Отмена',
    fromLabel: 'С',
    toLabel: 'По',
    customRangeLabel: 'Custom',
    daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт','Сб'],
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    firstDay: 1
}

locale.ru.dateFormat = 'DD.MM.YYYY';

function getLocale(v, l) {
    if (typeof l == 'undefined' && typeof window.LANG !== 'undefined') {
        l = window.LANG;
    }

    if (typeof locale[l][v] != 'undefined') {
        return locale[l][v]
    } else {
        return null;
    }
}

