Booking application
=======================================

ИНСТРУКЦИИ ПО ЗАПУСКУ В WINDOWS
-------------------------------

В системе должны быть установлены:

1. Git [Скачать](http://git-scm.com/download/win)
2. Virtual Box [Скачать](https://www.virtualbox.org/wiki/Downloads)
3. Vagrant [Скачать](https://www.vagrantup.com/downloads.html)

Клонирование репозитория

	git clone git@twebdev.ru:~/booking.git [ПУТЬ ДЛЯ КЛОНИРОВАНИЯ]
	cd [ПУТЬ ДЛЯ КЛОНИРОВАНИЯ]

Создание виртуального окружения Vagrant (продолжительная операция)

    vagrant up

Добавить в файл (Windows)

	c:\windows\System32\drivers\etc\hosts
или (Linux)

	/etc/hosts
строки

    192.168.10.10 booking.local
    192.168.10.10 backend.booking.local
    192.168.10.10 partner.booking.local

Подключение к виртуальной машине

    vagrant ssh

Домены

- http://booking.local
- http://backend.booking.local
- http://partner.booking.local
	
phpMyadmin:

	http://192.168.10.10/phpmyadmin