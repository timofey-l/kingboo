<?php

namespace common\components;

use yii\web\UrlManager;
use common\models\Lang;

class LangUrlManager extends UrlManager {

    /**
     * @inheritdoc
     */
    public function createUrl($params) {
        if (isset($params['lang_id'])) {
            // Если язык указан - пробуем найди в БД
            // иначе работем с языком по умолчанию
            $lang = Lang::findOne($params['lang_id']);
            if ($lang === null) {
                $lang = Lang::getDefaultLang();
            }
            unset($params['lang_id']);
        } else {
            // если не указан параметр языка - работаем с текущим
            $lang = Lang::getCurrent();
        }

        // получаем url без префикса языка
        $url = parent::createUrl($params);

        return '/' . $lang->url . $url;
    }
}