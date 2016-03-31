<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;
use Utilities\Service\Time;
use Users\Entity\User;

class PosBBIssues extends AbstractSeed
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


        $normalUser = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\User", array(
            "username" => "user"
        ));



        /**
         * Creating Issue Categories
         */
        $Sub3 = $this->serviceManager->get("wrapperQuery")->findOneBy("IssueTracker\Entity\IssueCategory", array(
            "title" => "Sub-Category 2-1"
        ));

        $Sub4 = $this->serviceManager->get("wrapperQuery")->findOneBy("IssueTracker\Entity\IssueCategory", array(
            "title" => "Sub-Category 2-1-1"
        ));

        /**
         * Creating Issues
         * 
         */
        $issue1 = array(
            'title' => $faker->name,
            'description' => implode(" ", $faker->sentences),
            'category_id' => $Sub3->getId(),
            'user_id' => $normalUser->getId(),
            'created' => date('Y-m-d H:i:s'),
            'status' => 1,
            'filePath' => null
        );
        $this->insert('issue', $issue1);


        $issue2 = array(
            'title' => $faker->name,
            'description' => implode(" ", $faker->sentences),
            'category_id' => $Sub4->getId(),
            'user_id' => $normalUser->getId(),
            'created' => date('Y-m-d H:i:s'),
            'status' => 1,
            'filePath' => null
        );
        $this->insert('issue', $issue2);


        $issue3 = array(
            'title' => $faker->name,
            'description' => implode(" ", $faker->sentences),
            'category_id' => $Sub3->getId(),
            'user_id' => $normalUser->getId(),
            'created' => date('Y-m-d H:i:s'),
            'status' => 1,
            'filePath' => null
        );
        $this->insert('issue', $issue3);


        $issue4 = array(
            'title' => $faker->name,
            'description' => implode(" ", $faker->sentences),
            'category_id' => $Sub3->getId(),
            'user_id' => $normalUser->getId(),
            'created' => date('Y-m-d H:i:s'),
            'status' => 1,
            'filePath' => null
        );
        $this->insert('issue', $issue4);


        $issue5 = array(
            'title' => $faker->name,
            'description' => implode(" ", $faker->sentences),
            'category_id' => $Sub4->getId(),
            'user_id' => $normalUser->getId(),
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'filePath' => null
        );
        $this->insert('issue', $issue5);
    }

}
