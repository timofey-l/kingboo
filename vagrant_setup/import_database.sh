#!/usr/bin/env bash

# Генерируем ssh ключ, если ключ уже существует - ничего не произойдёт
echo "Generating ssh key..."
echo -e "\n\n\n" | ssh-keygen -t rsa
echo "done!\n"

echo "Copy key to king-boo.com for login without password later..."
ssh-copy-id root@king-boo.com
echo "done!\n"

echo "Coping database dump from king-boo.com"
scp root@king-boo.com:/backups/database-latest.sql.zip ./database-latest.sql.zip

APP_DB_USER=kingboo
APP_DB_PASS=kingboo
APP_DB_NAME=$APP_DB_USER

cat << EOF | sudo su - postgres -c psql
-- Drop existing database
DROP DATABASE $APP_DB_NAME;
DROP USER $APP_DB_USER;
-- Create the database user:
CREATE USER $APP_DB_USER WITH PASSWORD '$APP_DB_PASS';

-- Create the database:
CREATE DATABASE $APP_DB_NAME WITH OWNER=$APP_DB_USER
                                  LC_COLLATE='en_US.utf8'
                                  LC_CTYPE='en_US.utf8'
                                  ENCODING='UTF8'
                                  TEMPLATE=template0;
EOF

# Копирование и заполнение параметров
scp root@king-boo.com:/kingboo/common/config/main-local.php /vagrant/common/config/main-local.php

cat << EOF > /vagrant/common/config/params-local.php
<?php
return [
    'mainDomain' => 'www.booking.local',
    'mainProtocol' => 'http',
    'mainDomainShort' => 'booking.local',
    'partnerDomain' => 'partner.booking.local',
    'partnerProtocol' => 'http',
    'hotelsDomain' => 'booking.local',
];
EOF

echo "";
sudo su postgres -c "unzip -p database-latest.sql.zip | psql"
rm ./database-latest.sql.zip

echo
