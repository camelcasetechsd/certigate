<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;
use \Users\Entity\User as User;
use \Utilities\Service\Status as Status;

class Issues extends AbstractSeed
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

        /**
         * Creating Issue Categories
         */
        $defaultCategory = array(
            'title' => 'Default Category',
            'description' => $faker->sentence,
            'weight' => 0,
            'depth' => 1,
            'parent_id' => null,
        );
        $this->insert('issue_category', $defaultCategory);
        $defaultCategoryId = $this->getAdapter()->getConnection()->lastInsertId();

        $Category1 = array(
            'title' => 'Category 1',
            'description' => $faker->sentence,
            'depth' => 1,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => null,
        );
        $this->insert('issue_category', $Category1);
        $Category1Id = $this->getAdapter()->getConnection()->lastInsertId();


        $Category2 = array(
            'title' => 'Category 2',
            'description' => $faker->sentence,
            'depth' => 1,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => null,
        );
        $this->insert('issue_category', $Category2);
        $Category2Id = $this->getAdapter()->getConnection()->lastInsertId();


        $Category3 = array(
            'title' => 'Category 3',
            'description' => $faker->sentence,
            'depth' => 1,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => null,
        );
        $this->insert('issue_category', $Category3);
        $Category3Id = $this->getAdapter()->getConnection()->lastInsertId();


        $Category4 = array(
            'title' => 'Category 4',
            'description' => $faker->sentence,
            'depth' => 1,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => null,
        );
        $this->insert('issue_category', $Category4);
        $Category4Id = $this->getAdapter()->getConnection()->lastInsertId();

        $Sub1 = array(
            'title' => 'Sub-Category 1-1',
            'description' => $faker->sentence,
            'depth' => 2,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => $Category1Id,
        );
        $this->insert('issue_category', $Sub1);
        $Sub1Id = $this->getAdapter()->getConnection()->lastInsertId();


        $Sub2 = array(
            'title' => 'Sub-Category 1-2',
            'description' => $faker->sentence,
            'depth' => 2,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => $Category1Id,
        );
        $this->insert('issue_category', $Sub2);
        $Sub2Id = $this->getAdapter()->getConnection()->lastInsertId();

        $Sub3 = array(
            'title' => 'Sub-Category 2-1',
            'description' => $faker->sentence,
            'depth' => 2,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => $Category2Id,
        );
        $this->insert('issue_category', $Sub3);
        $Sub3Id = $this->getAdapter()->getConnection()->lastInsertId();

        $Sub4 = array(
            'title' => 'Sub-Category 2-1-1',
            'description' => $faker->sentence,
            'depth' => 3,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => $Sub3Id,
        );
        $this->insert('issue_category', $Sub4);
        $Sub4Id = $this->getAdapter()->getConnection()->lastInsertId();



        /**
         * Creating Issues
         * 
         */
        // dummy user to use his id ad foreign key in orgs
        $normalUser = array(
            "firstName" => "QA",
            "middleName" => "Issue",
            "lastName" => "Tester",
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "creator",
            "password" => User::hashPassword("creator"),
            "mobile" =>"444-444-4444",
            "addressOne" => $faker->address,
            "addressTwo" => $faker->address,
            "city" => $faker->city,
            "zipCode" => $faker->postcode,
            "phone" =>"444-444-4444",            "nationality" => $faker->countryCode,
            "identificationType" => $faker->word,
            "identificationNumber" => $faker->numberBetween(/* $min = */ 999999),
            "identificationExpiryDate" => $faker->dateTimeBetween(/* $startDate = */ '+2 years', /* $endDate = */ '+20 years')->format('Y-m-d H:i:s'),
            "email" => $faker->freeEmail,
            "securityQuestion" => $faker->sentence,
            "securityAnswer" => $faker->sentence,
            "dateOfBirth" => date('Y-m-d H:i:s'),
            "photo" => '/upload/images/userdefault.png',
            "privacyStatement" => true,
            "studentStatement" => false,
            "proctorStatement" => false,
            "instructorStatement" => false,
            "testCenterAdministratorStatement" => false,
            "trainingManagerStatement" => false,
            "status" => true
        );
        $this->insert('user', $normalUser);
        $normalUserId = $this->getAdapter()->getConnection()->lastInsertId();

        $issue1 = array(
            'title' => $faker->name,
            'description' => implode(" ",$faker->sentences),
            'category_id' => $Sub3Id,
            'user_id' => $normalUserId,
            'created' => date('Y-m-d H:i:s'),
            'status' => 1,
            'filePath' => null
        );
        $this->insert('issue', $issue1);


        $issue2 = array(
            'title' => $faker->name,
            'description' => implode(" ",$faker->sentences),
            'category_id' => $Sub4Id,
            'user_id' => $normalUserId,
            'created' => date('Y-m-d H:i:s'),
            'status' => 1,
            'filePath' => null
        );
        $this->insert('issue', $issue2);


        $issue3 = array(
            'title' => $faker->name,
            'description' => implode(" ",$faker->sentences),
            'category_id' => $Sub3Id,
            'user_id' => $normalUserId,
            'created' => date('Y-m-d H:i:s'),
            'status' => 1,
            'filePath' => null
        );

        $this->insert('issue', $issue3);

        $issue4 = array(
            'title' => $faker->name,
            'description' => implode(" ",$faker->sentences),
            'category_id' => $Sub3Id,
            'user_id' => $normalUserId,
            'created' => date('Y-m-d H:i:s'),
            'status' => 1,
            'filePath' => null
        );
        $this->insert('issue', $issue4);

        $issue5 = array(
            'title' => $faker->name,
            'description' => implode(" ",$faker->sentences),
            'category_id' => $Sub4Id,
            'user_id' => $normalUserId,
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'filePath' => null
        );
        $this->insert('issue', $issue5);
    }

}
