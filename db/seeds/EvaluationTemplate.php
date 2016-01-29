<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;

class EvaluationTemplate extends AbstractSeed
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

        // dummy user to use his id ad foreign key in orgs
        $evaluationTemplate = array(
            'course_id' => null,
            'isTemplate' => 1,
            'isApproved' => 1
        );

        $this->insert('evaluation', $evaluationTemplate);
        $evaluationTemplateId = $this->getAdapter()->getConnection()->lastInsertId();

        $q1[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Course Content Met Your Needs'
        );
        $q2[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Matched Description in Course Guide'
        );
        $q3[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Pace of the Class'
        );
        $q4[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Instructor Established Positive Rapport With Students'
        );
        $q5[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Instructor Knowledge of the Subject Matter'
        );

        $this->insert('question', $q1);
        $this->insert('question', $q2);
        $this->insert('question', $q3);
        $this->insert('question', $q4);
        $this->insert('question', $q5);
    }

}
