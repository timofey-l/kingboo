var LANGS = {};

LANGS.ru = {
    image_list_title : "Фотографии",
    image_list_empty : "Нет загруженных фотографий",

    cancel: 'Отмена',
    ok: 'Подтвердить',
    
    image_add : "Добавить фотографию",
    confirm_action: 'Подтвердите действие',
    delete_confirm: 'Вы уверены, что хотите удалить фотографию?\n' +
    'В случае продолжения фотография будет удалена безвозвратно!\n',
    select_file: "Выберите файл изображения",
    send: 'Отправить',
    need_image_select: 'Необходимо сначала выбрать изображение',
};

LANGS.en = {
    image_list_title : "Photos",
    image_list_empty : "No photos uploaded",
    
    cancel: 'Cancel',
    ok: 'Ok',

    image_add : "Add photo",
    confirm_action: 'Confirm action',
    delete_confirm : 'Are you sure want to delete this photo?\n',
    select_file: "Select image file",
    send: 'Send',
    need_image_select: 'You need to choose image',
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