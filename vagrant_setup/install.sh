#!/bin/bash

# копируем конфигурационные файлы
cp -r /vagrant/vagrant_setup/dot_files/* ~/
cp /vagrant/vagrant_setup/dot_files/.bashrc ~/.bashrc

# обновление apt, установка дополнительного софта, копирование виртуальных хостов
# и перезапуск служб
sudo apt-get update
sudo service apache2 stop
sudo cp /vagrant/vagrant_setup/vhosts/* /etc/apache2/sites-available/
sudo a2ensite *.conf
sudo apt-get install php5-intl php5-apcu php5-readline php5-xdebug -y
sudo service apache2 start
cd /vagrant

composer selfupdate
composer global require "fxp/composer-asset-plugin:1.0.0"
composer update

php ./init --env=Development --overwrite=All
sed -i -e 's/\x27\x27/\x27root\x27/g' /vagrant/common/config/main-local.php
sed -i -e 's/dbname=yii2advanced/dbname=booking/g' /vagrant/common/config/main-local.php

mysql -uroot -proot -e 'CREATE SCHEMA booking DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;'

./yii migrate/up --interactive=0

cd /vagrant/frontend/web/
ln -s '../../common/uploads' 'uploads'
cd /vagrant/partner/web/
ln -s '../../common/uploads' 'uploads'