# composer 
composer install

## prepare Grunt libraries
npm update npm
npm install

# creating schema
bin/doctrine orm:schema-tool:drop --force;
bin/doctrine orm:schema-tool:update --force;

# seeding data
app_env=${APPLICATION_ENV:-'vagrant'}
./vendor/bin/phinx seed:run -e $app_env
php public/q2a/updateDB.php

## prepare public resources
cd public
bower install 
cd ../

## run Grunt tasks
./node_modules/.bin/grunt
