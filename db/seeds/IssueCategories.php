<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;
use \Users\Entity\User as User;
use \IssueTracker\Service\DepthLevel;
use IssueTracker\Entity\IssueCategory;

class IssueCategories extends AbstractSeed
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
    }

}
