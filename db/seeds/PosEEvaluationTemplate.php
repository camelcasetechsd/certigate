<?php

require_once __DIR__.'/../AbstractSeed.php';

use db\AbstractSeed;

class PosEEvaluationTemplate extends AbstractSeed
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
            'status' => true
        );

        $this->insert('evaluation', $evaluationTemplate);
        $evaluationTemplateId = $this->getAdapter()->getConnection()->lastInsertId();

        $q1[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Course Content Met Your Needs',
            'questionTitleAr' => 'Course Content Met Your Needs',
            'status' => 1
        );
        $q2[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Matched Description in Course Guide',
            'questionTitleAr' => 'Matched Description in Course Guide',
            'status' => 1
        );
        $q3[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Pace of the Class',
            'questionTitleAr' => 'Pace of the Class',
            'status' => 1
        );
        $q4[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Instructor Established Positive Rapport With Students',
            'questionTitleAr' => 'Instructor Established Positive Rapport With Students',
            'status' => 1
        );
        $q5[] = array(
            'evaluation_id' => $evaluationTemplateId,
            'questionTitle' => 'Instructor Knowledge of the Subject Matter',
            'questionTitleAr' => 'Instructor Knowledge of the Subject Matter',
            'status' => 1
        );

        $this->insert('question', $q1);
        $this->insert('question', $q2);
        $this->insert('question', $q3);
        $this->insert('question', $q4);
        $this->insert('question', $q5);
    }

}
