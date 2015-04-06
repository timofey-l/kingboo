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
    room_table_head_delete: "Удалить",

    room_adults_title: "Взрослых",
    room_children_title: "Детей",
    room_total_title: "Всего",

    room_list_empty: "Номера отсутствуют!",

    room_list_title: "Список номеров",
    room_edit_title: "Редактирование номера",
    room_save : "Сохранить",
    room_null_text: "Такого номера не найдено.",
    room_cancel : "Отмена"
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
    room_table_head_total: "Title",
    room_table_head_delete: "Delete",

    room_adults_title: "Adults",
    room_children_title: "Children",
    room_total_title: "Total",

    room_list_empty: "Rooms are absent!",

    room_list_title: "Rooms list",
    room_edit_title: "Edit room",
    room_save : "Save",
    room_null_text: "Такого номера не найдено.",
    room_cancel : "Cancel"
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