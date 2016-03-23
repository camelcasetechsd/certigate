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
php public/q2a/updateDB.php
php public/estore/updateDB.php
php public/forum/updateDB.php
./vendor/bin/phinx seed:run -e $app_env


## prepare public resources
cd public
bower install 
cd ../

## run Grunt tasks
./node_modules/.bin/grunt
