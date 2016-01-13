<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;
use \Users\Entity\User;
use \Users\Entity\Role;

class Users extends AbstractSeed {

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run() {
        $faker = Faker\Factory::create();
        
        $userRole = array('name' => 'User');
        $this->insert('role', $userRole);
        $normalUserRoleId = $this->getAdapter()->getConnection()->lastInsertId();
        
        $adminRole = array('name' => Role::ADMIN_ROLE);
        $this->insert('role', $adminRole);
        $adminRoleId = $this->getAdapter()->getConnection()->lastInsertId();
// * @property string $addressOne
// * @property string $addressTwo
// * @property string $city
// * @property string $zipCode
// * @property string $phone
// * @property string $nationality
// * @property string $identificationType
// * @property string $identificationNumber
// * @property \DateTime $identificationNumberExpiryDate
// * @property string $email
// * @property string $securityQuestion
// * @property string $securityAnswer   
        $adminUser = array(
                "firstName" => $faker->firstName,
                "middleName" => $faker->name,
                "lastName" => $faker->lastName,
                "country" => $faker->countryCode,
                "language" => $faker->languageCode,
                "username" => "admin",
                "password" => User::hashPassword("adminadmin"),
                "mobile" => $faker->phoneNumber,
                "addressOne" => $faker->address,
                "addressTwo" => $faker->address,
                "city" => $faker->city,
                "zipCode" => $faker->postcode,
                "phone" => $faker->phoneNumber,
                "nationality" => $faker->countryCode,
                "identificationType" => $faker->word,
                "identificationNumber" => $faker->numberBetween(/*$min =*/ 999999),
                "identificationExpiryDate" => $faker->dateTimeBetween(/*$startDate =*/ '+2 years', /*$endDate =*/ '+20 years')->format('Y-m-d H:i:s'),
                "email" => $faker->freeEmail,
                "securityQuestion" => $faker->sentence,
                "securityAnswer" => $faker->sentence,
                "dateOfBirth" => date('Y-m-d H:i:s'),
                "photo" => '/upload/images/userdefault.png',
                "status" => true
            );
        $this->insert('user', $adminUser);
        $adminUserId = $this->getAdapter()->getConnection()->lastInsertId();
        
        $normalUser = array(
                "firstName" => $faker->firstName,
                "middleName" => $faker->name,
                "lastName" => $faker->lastName,
                "country" => $faker->countryCode,
                "language" => $faker->languageCode,
                "username" => "user",
                "password" => User::hashPassword("useruser"),
                "mobile" => $faker->phoneNumber,
                "addressOne" => $faker->address,
                "addressTwo" => $faker->address,
                "city" => $faker->city,
                "zipCode" => $faker->postcode,
                "phone" => $faker->phoneNumber,
                "nationality" => $faker->countryCode,
                "identificationType" => $faker->word,
                "identificationNumber" => $faker->numberBetween(/*$min =*/ 999999),
                "identificationExpiryDate" => $faker->dateTimeBetween(/*$startDate =*/ '+2 years', /*$endDate =*/ '+20 years')->format('Y-m-d H:i:s'),
                "email" => $faker->freeEmail,
                "securityQuestion" => $faker->sentence,
                "securityAnswer" => $faker->sentence,
                "dateOfBirth" => date('Y-m-d H:i:s'),
                "photo" => '/upload/images/userdefault.png',
                "status" => true
            );
        $this->insert('user', $normalUser);
        $normalUserId = $this->getAdapter()->getConnection()->lastInsertId();
        
        $userRoles = array(array(
            'user_id' => $adminUserId,
            'role_id' => $adminRoleId
                ), array(
            'user_id' => $normalUserId,
            'role_id' => $normalUserRoleId
        ));
        $this->insert('user_role', $userRoles);
    }

}
