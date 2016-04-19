<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;

class PosBAIssuesCategories extends AbstractSeed
{

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

        $Category4 = array(
            'title' => 'Category 4',
            'description' => $faker->sentence,
            'depth' => 1,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => null,
        );
        $this->insert('issue_category', $Category4);

        $Sub1 = array(
            'title' => 'Sub-Category 1-1',
            'description' => $faker->sentence,
            'depth' => 2,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => $Category1Id,
        );
        $this->insert('issue_category', $Sub1);

        $Sub2 = array(
            'title' => 'Sub-Category 1-2',
            'description' => $faker->sentence,
            'depth' => 2,
            'weight' => $faker->numberBetween(0, 1000),
            'parent_id' => $Category1Id,
        );
        $this->insert('issue_category', $Sub2);

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
