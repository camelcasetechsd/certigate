<?php

namespace Users\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Users\Entity\User as UserEntity;
use Users\Fixture\UserCredentials;
use Utilities\Service\Status;
use Utilities\Service\Time;
use Utilities\Service\Inflector;

/**
 * User Fixture
 * 
 * @package users
 * @subpackage fixture
 */
class User extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load user fixture
     * 
     * @access public
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $userCredentials = UserCredentials::$userCredentials;
        $inflector = new Inflector();

        foreach ($userCredentials as $userName => $userCredential) {
            $user = new UserEntity();
            $date = new \DateTime();
            $user->setFirstName($faker->firstName)
                    ->setMiddleName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setCountry($faker->countryCode)
                    ->setLanguage($faker->languageCode)
                    ->setUsername($userName)
                    ->setPassword(UserEntity::hashPassword($userCredential["password"]))
                    ->setMobile($faker->numberBetween(/* $min = */ 1000000000, /* $max = */ 2000000000))
                    ->setAddressOne($faker->address)
                    ->setAddressTwo($faker->address)
                    ->setCity($faker->city)
                    ->setZipCode($faker->postcode)
                    ->setPhone($faker->numberBetween(/* $min = */ 1000000000, /* $max = */ 2000000000))
                    ->setNationality($faker->countryCode)
                    ->setIdentificationType($faker->word)
                    ->setIdentificationNumber($faker->numberBetween(/* $min = */ 999999))
                    ->setIdentificationExpiryDate($faker->dateTimeBetween(/* $startDate = */ '+2 years', /* $endDate = */ '+20 years')->format(Time::DATE_FORMAT))
                    ->setEmail($faker->freeEmail)
                    ->setSecurityQuestion($faker->sentence)
                    ->setSecurityAnswer($faker->sentence)
                    ->setDateOfBirth($date->format(Time::DATE_FORMAT))
                    ->setPhoto('/upload/images/userdefault.png')
                    ->setPrivacyStatement(true)
                    ->setStatus(Status::STATUS_ACTIVE)
                    ->setStudentStatement(Status::STATUS_INACTIVE)
                    ->setInstructorStatement(Status::STATUS_INACTIVE)
                    ->setProctorStatement(Status::STATUS_INACTIVE)
                    ->setTestCenterAdministratorStatement(Status::STATUS_INACTIVE)
                    ->setTrainingManagerStatement(Status::STATUS_INACTIVE)
            ;
            $approvedStatementMethod = "set" . $inflector->camelize("{$userName}Statement");
            if (method_exists($user, $approvedStatementMethod)) {
                $user->$approvedStatementMethod(Status::STATUS_ACTIVE);
            }
            $manager->persist($user);
            $this->addReference($userName . "User", $user);
        }

        $manager->flush();
    }

    /**
     * Get Fixture order
     * 
     * @access public
     * @return int
     */
    public function getOrder()
    {
        return 3; // number in which order to load fixtures
    }

}
