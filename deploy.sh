# composer 
composer install

## prepare Grunt libraries
npm update npm
npm install

# creating schema
./vendor/bin/doctrine-module orm:schema-tool:drop --force;
./vendor/bin/doctrine-module orm:schema-tool:update --force;

# seeding data
app_env=${APPLICATION_ENV:-'vagrant'}
./vendor/bin/phinx seed:run -e $app_env

## prepare public resources
cd public
bower install 
cd ../

## run Grunt tasks
./node_modules/.bin/grunt