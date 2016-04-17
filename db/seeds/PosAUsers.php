<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;
use Users\Entity\User;
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
        $TMRole = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\Role", array("name" => Role::TRAINING_MANAGER_ROLE));
        $TCARole = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\Role", array("name" => Role::TEST_CENTER_ADMIN_ROLE));
        $instructorRole = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\Role", array("name" => Role::INSTRUCTOR_ROLE));
        $studentRole = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\Role", array("name" => Role::STUDENT_ROLE));
        
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
            ),
            'longitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
        );

        $userModel = $this->serviceManager->get("Users\Model\User");
        $userModel->saveUser($adminUser, /* $userObj = */ new User(), /* $isAdminUser = */ true, /* $editFormFlag = */ false);
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
            ),
            'longitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
        );
        $userModel->saveUser($normalUser, /* $userObj = */ new User(), /* $isAdminUser = */ true, /* $editFormFlag = */ false);

        $TMUser = array(
            "firstName" => $faker->firstName,
            "firstNameAr" => $faker->firstName,
            "middleName" => $faker->name,
            "middleNameAr" => $faker->name,
            "lastName" => $faker->lastName,
            "lastNameAr" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "tmuser",
            "password" => "tmuser",
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
                $TMRole->getId()
            ),
            'longitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
        );
        $userModel->saveUser($TMUser, /* $userObj = */ new User(), /* $isAdminUser = */ true, /* $editFormFlag = */ false);
        // test center admin
        $TCAUser = array(
            "firstName" => $faker->firstName,
            "firstNameAr" => $faker->firstName,
            "middleName" => $faker->name,
            "middleNameAr" => $faker->name,
            "lastName" => $faker->lastName,
            "lastNameAr" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "tcauser",
            "password" => "tcauser",
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
                $TCARole->getId()
            ),
            'longitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
        );
        $userModel->saveUser($TCAUser, /* $userObj = */ new User(), /* $isAdminUser = */ true, /* $editFormFlag = */ false);


        $instructorUser = array(
            "firstName" => $faker->firstName,
            "firstNameAr" => $faker->firstName,
            "middleName" => $faker->name,
            "middleNameAr" => $faker->name,
            "lastName" => $faker->lastName,
            "lastNameAr" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "instructor",
            "password" => "instructor",
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
                $instructorRole->getId()
            ),
            'longitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
        );
        $userModel->saveUser($instructorUser, /* $userObj = */ new User(), /* $isAdminUser = */ true, /* $editFormFlag = */ false);

        $studentUser = array(
            "firstName" => $faker->firstName,
            "firstNameAr" => $faker->firstName,
            "middleName" => $faker->name,
            "middleNameAr" => $faker->name,
            "lastName" => $faker->lastName,
            "lastNameAr" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "student",
            "password" => "student",
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
                $studentRole->getId()
            ),
            'longitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
        );
        $userModel->saveUser($studentUser, /* $userObj = */ new User(), /* $isAdminUser = */ true, /* $editFormFlag = */ false);
    }

}
