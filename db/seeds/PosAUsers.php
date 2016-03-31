<?php

require_once __DIR__.'/../AbstractSeed.php';

use db\AbstractSeed;
use \Users\Entity\User;
use Users\Entity\Role;
use Utilities\Service\Time;

class PosAUsers extends AbstractSeed
{

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $adminRole = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\Role", array("name" => Role::ADMIN_ROLE));
        $adminUser = array(
            "firstName" => $faker->firstName,
            "firstNameAr" => $faker->firstName,
            "middleName" => $faker->name,
            "middleNameAr" => $faker->name,
            "lastName" => $faker->lastName,
            "lastNameAr" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "admin",
            "password" => "adminadmin",
            "mobile" => $faker->phoneNumber,
            "addressOne" => $faker->address,
            "addressOneAr" => $faker->address,
            "addressTwo" => $faker->address,
            "addressTwoAr" => $faker->address,
            "city" => $faker->city,
            "zipCode" => $faker->postcode,
            "phone" => $faker->phoneNumber,
            "nationality" => $faker->countryCode,
            "identificationType" => $faker->word,
            "identificationNumber" => $faker->numberBetween(/* $min = */ 999999),
            "identificationExpiryDate" => $faker->dateTimeBetween(/* $startDate = */ '+2 years', /* $endDate = */ '+20 years')->format(Time::DATE_FORMAT),
            "email" => $faker->freeEmail,
            "securityQuestion" => $faker->sentence,
            "securityAnswer" => $faker->sentence,
            "dateOfBirth" => date(Time::DATE_FORMAT),
            "photo" => '/upload/images/userdefault.png',
            "privacyStatement" => true,
            "studentStatement" => false,
            "proctorStatement" => false,
            "instructorStatement" => false,
            "testCenterAdministratorStatement" => false,
            "trainingManagerStatement" => false,
            "status" => true,
            "roles" => array(
                $adminRole->getId()
            )
        );
        $userModel = $this->serviceManager->get("Users\Model\User");
        $userModel->saveUser($adminUser, /*$userObj =*/ new User(), /*$isAdminUser =*/ true, /*$editFormFlag =*/ false);
        $normalUser = array(
            "firstName" => $faker->firstName,
            "firstNameAr" => $faker->firstName,
            "middleName" => $faker->name,
            "middleNameAr" => $faker->name,
            "lastName" => $faker->lastName,
            "lastNameAr" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "user",
            "password" => "useruser",
            "mobile" => $faker->phoneNumber,
            "addressOne" => $faker->address,
            "addressOneAr" => $faker->address,
            "addressTwo" => $faker->address,
            "addressTwoAr" => $faker->address,
            "city" => $faker->city,
            "zipCode" => $faker->postcode,
            "phone" => $faker->phoneNumber,
            "nationality" => $faker->countryCode,
            "identificationType" => $faker->word,
            "identificationNumber" => $faker->numberBetween(/* $min = */ 999999),
            "identificationExpiryDate" => $faker->dateTimeBetween(/* $startDate = */ '+2 years', /* $endDate = */ '+20 years')->format(Time::DATE_FORMAT),
            "email" => $faker->freeEmail,
            "securityQuestion" => $faker->sentence,
            "securityAnswer" => $faker->sentence,
            "dateOfBirth" => date(Time::DATE_FORMAT),
            "photo" => '/upload/images/userdefault.png',
            "privacyStatement" => true,
            "studentStatement" => false,
            "proctorStatement" => false,
            "instructorStatement" => false,
            "testCenterAdministratorStatement" => false,
            "trainingManagerStatement" => false,
            "status" => true,
            "roles" => array(
            )
        );
        $userModel->saveUser($normalUser, /*$userObj =*/ new User(), /*$isAdminUser =*/ true, /*$editFormFlag =*/ false);
    }

}
