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

        for ($i = 0; $i < 70; $i++) {
            $i % 2 == 0 ? $categoryId = $Sub3->getId() : $categoryId = $Sub4->getId();
            $issue = array(
                'title' => $faker->name,
                'description' => implode(" ", $faker->sentences),
                'category_id' => $categoryId,
                'user_id' => $normalUser->getId(),
                'status' => 1,
                'created' => date('Y-m-d H:i:s'),
                'filePath' => null
            );
            $this->insert('issue', $issue);
        }
    }

}
