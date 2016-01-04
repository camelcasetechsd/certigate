# composer 
composer install

# creating schema
./vendor/bin/doctrine-module orm:schema-tool:drop --force;
./vendor/bin/doctrine-module orm:schema-tool:update --force;

# seeding data
./vendor/bin/phinx seed:run