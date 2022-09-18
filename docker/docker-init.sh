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
#security-checker security:check
#symfony check:security
#symfony console about

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
echo "-                        php-cs-fixer                             -"
echo "-------------------------------------------------------------------"
#php-cs-fixer fix ./src --rules=@Symfony --verbose --show-progress=estimating

echo "-------------------------------------------------------------------"
echo "-                        phpstan                                  -"
echo "-------------------------------------------------------------------"
#vendor/bin/phpstan analyse src
#./vendor/bin/psalm

echo "-------------------------------------------------------------------"
echo "-                        php-doc-check                            -"
echo "-------------------------------------------------------------------"
#vendor/bin/php-doc-check ./src

echo "-------------------------------------------------------------------"
echo "-                        phpcpd                                   -"
echo "-------------------------------------------------------------------"
#phpcpd --no-interaction src

echo "-------------------------------------------------------------------"
echo "-                        PHPMD                                    -"
echo "-------------------------------------------------------------------"
#phpmd ./src text codesize,unusedcode,naming,design,controversial,cleancode

echo "-------------------------------------------------------------------"
echo "-                            benchmarks                           -"
echo "-------------------------------------------------------------------"
#phpbench run --report=default --report=env
#phpbench xdebug:profile
#phpbench xdebug:trace

echo "-------------------------------------------------------------------"
echo "-                        website is ready                         -"
echo "-------------------------------------------------------------------"
#symfony proxy:start
#symfony proxy:domain:attach my-domain
#HTTPS_PROXY=http://127.0.0.1:7080 curl https://my-domain.wip
chmod -R a+rw ./
symfony check:requirements
symfony security:check
#echo | symfony server:ca:install
symfony server:start --daemon

echo "-------------------------------------------------------------------"
echo "-                        testing                                  -"
echo "-------------------------------------------------------------------"
codecept clean
codecept run --steps

echo "-------------------------------------------------------------------"
echo "-                        yarn watch                               -"
echo "-------------------------------------------------------------------"
#export PATH=node_modules/.bin/:$PATH
#export NODE_OPTIONS="--max_old_space_size=4096"
#npm run watch

symfony server:stop
symfony server:start