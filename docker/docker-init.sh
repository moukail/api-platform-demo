#!/usr/bin/env bash

# create new project
# . /home/install.sh

export APP_ENV=dev
#rm -rf var vendor composer.lock symfony.lock
cp .env.develop .env
echo "8.1" > .php-version

echo "---------------------------------------------------------------------------------------------------------------------------"
echo "-                                                composer                                                                 -"
echo "----------------------------------------------------------------------------------------------------------------------------"
symfony composer check-platform-reqs
composer install --no-interaction
symfony composer update --no-interaction #--no-plugins --no-scripts

symfony check:requirements
symfony check:security

echo "-------------------------------------------------------------------"
echo "-                        waiting for DB                           -"
echo "-------------------------------------------------------------------"
while ! nc -z paqt-database 3306; do sleep 1; done
echo "-------------------------------------------------------------------"
echo "-                        prepare the DB                           -"
echo "-------------------------------------------------------------------"
#symfony console doctrine:database:drop --if-exists --force
symfony console doctrine:database:create --if-not-exists
#symfony console doctrine:migrations:diff --no-interaction
symfony console doctrine:migrations:migrate --allow-no-migration --no-interaction
symfony console doctrine:fixtures:load --no-interaction -vvv

echo "-------------------------------------------------------------------"
echo "-                        website is ready                         -"
echo "-------------------------------------------------------------------"
chmod -R a+rw ./
symfony server:start --daemon

echo "-------------------------------------------------------------------"
echo "-                        testing                                  -"
echo "-------------------------------------------------------------------"
codecept clean
codecept run --steps

symfony server:stop
symfony server:start