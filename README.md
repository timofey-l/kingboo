=======================================
Booking application
=======================================

ИНСТРУКЦИИ ПО ЗАПУСКУ В WINDOWS
-------------------------------

В системе должны быть установлены:

1. Git [Скачать](http://git-scm.com/download/win)
2. Virtual Box [Скачать](https://www.virtualbox.org/wiki/Downloads)
3. Vagrant [Скачать](https://www.vagrantup.com/downloads.html)

Все комманды в Git Bash
Клонирование репозитория

    git clone git@twebdev.ru:~/booking.git [ПУТЬ ДЛЯ КЛОНИРОВАНИЯ]
    cd [ПУТЬ ДЛЯ КЛОНИРОВАНИЯ]

Создание виртуального окружения Vagrant (продолжительная операция)

    vagrant up

Добавить в файл

    c:\windows\System32\drivers\etc\hosts 

строки

    192.168.10.10 booking.local
    192.168.10.10 backend.booking.local
    192.168.10.10 partner.booking.local


Подключение к виртуальной машине по ssh и переход в рабочий каталог

    vagrant ssh
    cd /vagrant


Установка плагина для composer, необходимого для yii

    composer global require "fxp/composer-asset-plugin:1.0.0"


Обновление зависимостей Composer (потребуется ввод данных аккаунта в GitHub)

    composer update


Инициализация приложения
    
    php init
    
Далее выбираем Development и соглашаемся.

Редактируем файл 

    common/config/main-local.php

    ...
    'dsn' => 'mysql:host=localhost;dbname=booking',
    'username' => 'booking',
    'password' => 'booking',
    ...

Запускаем миграции
  
    php yii migrate/up


Добавляем админа

    php yii admin/add-admin



Готово!

Используя данные админа можно входить в бэкенд.
http://backend.booking.local
