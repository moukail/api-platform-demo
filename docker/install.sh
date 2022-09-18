#git config --global user.email "i.moukafih@gmail.com"
#git config --global user.name "Ismail Moukafih"

rm -rf ./*

echo "-------------------------------------------------------------------"
echo "-                create symfony project                           -"
echo "-------------------------------------------------------------------"
export APP_ENV=dev
symfony new web --version=5.4 #--full

echo "-------------------------------------------------------------------"
echo "-                   require packages                              -"
echo "-------------------------------------------------------------------"
cd ./web

cp .env .env.develop
sed -i '/# DATABASE_URL=/d' .env.develop # delete lines from file
sed -i 's/DATABASE_URL=.*/DATABASE_URL="mysql"/g' .env.develop
#sed -i 's/DATABASE_URL=.*/DATABASE_URL="mysql:\/\/app:!ChangeMe!@127.0.0.1:3306\/app?serverVersion=8\&charset=utf8mb4"/g' .env.develop

#symfony composer config extra.symfony.allow-contrib false
#symfony composer config --list
composer require --no-interaction api ramsey/uuid-doctrine sentry/sentry-symfony

echo "-------------------------------------------------------------------"
echo "-               require dev packages                              -"
echo "-------------------------------------------------------------------"
symfony composer require --no-interaction --dev doctrine/doctrine-fixtures-bundle symfony/profiler-pack symfony/maker-bundle
symfony composer require --dev codeception/codeception codeception/specify codeception/verify
symfony composer require --dev league/factory-muffin league/factory-muffin-faker
#symfony composer require --dev flow/jsonpath phpbench/phpbench
#symfony composer require --dev vimeo/psalm

rm -rf .git
cd ..

chmod -R a+rw web

rsync -a web/ ./
rm -rf web
