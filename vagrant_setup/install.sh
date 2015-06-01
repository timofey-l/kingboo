#!/bin/bash

php_config_file="/etc/php5/apache2/php.ini"
xdebug_config_file="/etc/php5/mods-available/xdebug.ini"
mysql_config_file="/etc/mysql/my.cnf"

# обновление apt, установка дополнительного софта, копирование виртуальных хостов
# и перезапуск служб
apt-get update
apt-get -y upgrade

# проверяем первый ли раз производится запуск скрипта
# если необходимо повторить provision, то сначала удалить файл
# /var/lock/vagrant-provision
# затем выполнить
#
if [[ -e /var/lock/vagrant-provision ]]; then
    exit;
fi

IPADDR=$(/sbin/ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://')
sed -i "s/^${IPADDR}.*//" /etc/hosts
echo $IPADDR ubuntu.localhost >> /etc/hosts

sed -i "s/#force_color_prompt/force_color_prompt/" /home/vagrant/.bashrc
echo "cd /vagrant" >> /home/vagrant/.bashrc

# Install basic tools
apt-get -y install build-essential binutils-doc git curl

apt-get -y install apache2
service apache2 stop
a2enmod rewrite
apt-get -y install php5 php5-curl php5-mysql php5-sqlite php5-xdebug php5-intl php5-apcu php5-readline php5-mcrypt
php5enmod mcrypt
sed -i "s/display_startup_errors = Off/display_startup_errors = On/g" ${php_config_file}
sed -i "s/display_errors = Off/display_errors = On/g" ${php_config_file}
cp /vagrant/vagrant_setup/vhosts/* /etc/apache2/sites-available/
a2ensite *.conf

# xdebug
cat << EOF > ${xdebug_config_file}
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
EOF

# mysql
echo "mysql-server mysql-server/root_password password root" | sudo debconf-set-selections
echo "mysql-server mysql-server/root_password_again password root" | sudo debconf-set-selections
apt-get -y install mysql-client mysql-server
sed -i "s/bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" ${mysql_config_file}
echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION" | mysql -u root --password=root
echo "GRANT PROXY ON ''@'' TO 'root'@'%' WITH GRANT OPTION" | mysql -u root --password=root
mysql -uroot -proot -e 'CREATE SCHEMA booking DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;'


echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password root" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password root" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password root" | debconf-set-selections
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none" | debconf-set-selections
apt-get -y install mysql-server-5.5 phpmyadmin > /dev/null 2>&1

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer global require "fxp/composer-asset-plugin:1.0.0"

cd /vagrant
composer update
php ./init --env=Development --overwrite=All
sed -i -e 's/\x27\x27/\x27root\x27/g' /vagrant/common/config/main-local.php
sed -i -e 's/dbname=yii2advanced/dbname=booking/g' /vagrant/common/config/main-local.php
php ./yii migrate/up --interactive=0
php ./yii admin/generate-loceanica

cd /vagrant/frontend/web/
ln -s '../../common/uploads' 'uploads'
cd /vagrant/partner/web/
ln -s '../../common/uploads' 'uploads'


service apache2 restart
service mysql restart
touch /var/lock/vagrant-provision