# retrieving Application Environment 
app_env=${APPLICATION_ENV:-'vagrant'} 

# composer 
composer install

## prepare Grunt libraries
npm update npm
npm install

# creating schema
APPLICATION_ENV=$app_env bin/doctrine orm:schema-tool:drop --force;
APPLICATION_ENV=$app_env bin/doctrine orm:schema-tool:update --force;

# seeding data

# seeding estore first 
APPLICATION_ENV=$app_env php public/estore/updateDB.php -e $app_env 

# running certigate seeds 
# send app_env as environment variable and command variable 
APPLICATION_ENV=$app_env ./vendor/bin/phinx seed:run -e $app_env 

# seeding q2a
APPLICATION_ENV=$app_env php public/q2a/updateDB.php

# seeding forum
APPLICATION_ENV=$app_env php public/forum/updateDB.php

## prepare public resources
cd public
bower install 
cd ../

## run Grunt tasks
./node_modules/.bin/grunt
