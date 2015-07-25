#!/bin/bash

php_config_file="/etc/php5/apache2/php.ini"
xdebug_config_file="/etc/php5/mods-available/xdebug.ini"
mysql_config_file="/etc/mysql/my.cnf"

# установка локали
locale-gen "ru_RU.UTF-8"
dpkg-reconfigure locales
echo "LANG=\"ru_RU.UTF-8\"" >> /etc/default/locale
echo "LC_ALL=\"ru_RU.UTF-8\"" >> /etc/default/locale

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

# install russian language pack
apt-get install language-pack-ru

apt-get -y install apache2
service apache2 stop
a2enmod rewrite
apt-get -y install php5 php5-pgsql php5-curl php5-mysql php5-sqlite php5-xdebug php5-intl php5-apcu php5-readline php5-mcrypt php5-imagick php5-gd
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
#echo "mysql-server mysql-server/root_password password root" | sudo debconf-set-selections
#echo "mysql-server mysql-server/root_password_again password root" | sudo debconf-set-selections
#apt-get -y install mysql-client mysql-server
#sed -i "s/bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" ${mysql_config_file}
#echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION" | mysql -u root --password=root
#echo "GRANT PROXY ON ''@'' TO 'root'@'%' WITH GRANT OPTION" | mysql -u root --password=root
#mysql -uroot -proot -e 'CREATE SCHEMA booking DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;'

# Edit the following to change the name of the database user that will be created:
APP_DB_USER=kingboo
APP_DB_PASS=kingboo

# Edit the following to change the name of the database that is created (defaults to the user name)
APP_DB_NAME=$APP_DB_USER

# Edit the following to change the version of PostgreSQL that is installed
PG_VERSION=9.4

###########################################################
# Changes below this line are probably not necessary
###########################################################
print_db_usage () {
  echo "Your PostgreSQL database has been setup and can be accessed on your local machine on the forwarded port (default: 15432)"
  echo "  Host: localhost"
  echo "  Port: 15432"
  echo "  Database: $APP_DB_NAME"
  echo "  Username: $APP_DB_USER"
  echo "  Password: $APP_DB_PASS"
  echo ""
  echo "Admin access to postgres user via VM:"
  echo "  vagrant ssh"
  echo "  sudo su - postgres"
  echo ""
  echo "psql access to app database user via VM:"
  echo "  vagrant ssh"
  echo "  sudo su - postgres"
  echo "  PGUSER=$APP_DB_USER PGPASSWORD=$APP_DB_PASS psql -h localhost $APP_DB_NAME"
  echo ""
  echo "Env variable for application development:"
  echo "  DATABASE_URL=postgresql://$APP_DB_USER:$APP_DB_PASS@localhost:15432/$APP_DB_NAME"
  echo ""
  echo "Local command to access the database via psql:"
  echo "  PGUSER=$APP_DB_USER PGPASSWORD=$APP_DB_PASS psql -h localhost -p 15432 $APP_DB_NAME"
}

export DEBIAN_FRONTEND=noninteractive

PROVISIONED_ON=/etc/vm_provision_on_timestamp
if [ -f "$PROVISIONED_ON" ]
then
  echo "VM was already provisioned at: $(cat $PROVISIONED_ON)"
  echo "To run system updates manually login via 'vagrant ssh' and run 'apt-get update && apt-get upgrade'"
  echo ""
  print_db_usage
  exit
fi

PG_REPO_APT_SOURCE=/etc/apt/sources.list.d/pgdg.list
if [ ! -f "$PG_REPO_APT_SOURCE" ]
then
  # Add PG apt repo:
  echo "deb http://apt.postgresql.org/pub/repos/apt/ trusty-pgdg main" > "$PG_REPO_APT_SOURCE"

  # Add PGDG repo key:
  wget --quiet -O - https://apt.postgresql.org/pub/repos/apt/ACCC4CF8.asc | apt-key add -
fi

apt-get update
apt-get -y install "postgresql-$PG_VERSION" "postgresql-contrib-$PG_VERSION"

PG_CONF="/etc/postgresql/$PG_VERSION/main/postgresql.conf"
PG_HBA="/etc/postgresql/$PG_VERSION/main/pg_hba.conf"
PG_DIR="/var/lib/postgresql/$PG_VERSION/main"

# Edit postgresql.conf to change listen address to '*':
sed -i "s/#listen_addresses = 'localhost'/listen_addresses = '*'/" "$PG_CONF"

# Append to pg_hba.conf to add password auth:
echo "host    all             all             all                     md5" >> "$PG_HBA"

# Explicitly set default client_encoding
echo "client_encoding = utf8" >> "$PG_CONF"

# Restart so that all new config is loaded:
service postgresql restart

cat << EOF | su - postgres -c psql
-- Create the database user:
CREATE USER $APP_DB_USER WITH PASSWORD '$APP_DB_PASS';

-- Create the database:
CREATE DATABASE $APP_DB_NAME WITH OWNER=$APP_DB_USER
                                  LC_COLLATE='en_US.utf8'
                                  LC_CTYPE='en_US.utf8'
                                  ENCODING='UTF8'
                                  TEMPLATE=template0;
EOF

# Tag the provision time:
date > "$PROVISIONED_ON"

echo "Successfully created PostgreSQL dev virtual machine."
echo ""
print_db_usage


#echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
#echo "phpmyadmin phpmyadmin/app-password-confirm password root" | debconf-set-selections
#echo "phpmyadmin phpmyadmin/mysql/admin-pass password root" | debconf-set-selections
#echo "phpmyadmin phpmyadmin/mysql/app-pass password root" | debconf-set-selections
#echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none" | debconf-set-selections
#apt-get -y install mysql-server-5.5 phpmyadmin > /dev/null 2>&1

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer global require "fxp/composer-asset-plugin:1.0.0"

cd /vagrant
composer update
php ./init --env=Development --overwrite=All
sed -i -e 's/mysql/pgsql/g' /vagrant/common/config/main-local.php
sed -i -e 's/\x27root\x27/\x27kingboo\x27/g' /vagrant/common/config/main-local.php
sed -i -e 's/\x27\x27/\x27kingboo\x27/g' /vagrant/common/config/main-local.php
sed -i -e 's/dbname=yii2advanced/dbname=kingboo/g' /vagrant/common/config/main-local.php
php ./yii migrate/up --interactive=0
php ./yii admin/generate-loceanica

cd /vagrant/frontend/web/
ln -s '../../common/uploads' 'uploads'
cd /vagrant/partner/web/
ln -s '../../common/uploads' 'uploads'


service apache2 restart
touch /var/lock/vagrant-provision