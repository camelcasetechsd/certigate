# composer 
composer install

# creating schema
bin/doctrine orm:schema-tool:drop --force;
bin/doctrine orm:schema-tool:update --force;

# seeding data

# seeding estore first 
php public/estore/updateDB.php

# running certigate seeds 
app_env=${APPLICATION_ENV:-'vagrant'}
./vendor/bin/phinx seed:run -e $app_env 

# seeding q2a
php public/q2a/updateDB.php

## prepare public resources
cd public
bower install 
cd ../

## run Grunt tasks
./node_modules/.bin/grunt
