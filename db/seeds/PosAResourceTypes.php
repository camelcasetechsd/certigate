<?php

require_once 'init_autoloader.php';

use Phinx\Seed\AbstractSeed;
use Courses\Entity\ResourceType as Type;

class PosAResourceTypes extends AbstractSeed
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
        /**
         * Declearing organization types
         */
        $presentations = array(
            "title" => Type::PRESENTATIONS_TYPE_TEXT,
        );

        $activities = array(
            "title" => Type::ACTIVITIES_TYPE_TEXT,
        );

        $exams = array(
            "title" => Type::EXAMS_TYPE_TEXT,
        );

        $courseUpdates = array(
            "title" => Type::COURSE_UPDATES_TYPE_TEXT,
        );

        $standards = array(
            "title" => Type::STANDARDS_TYPE_TEXT,
        );

        $iceBreakers = array(
            "title" => Type::ICE_BREAKERS_TYPE_TEXT,
        );

        $this->insert('resource_type', $presentations);
        $this->insert('resource_type', $activities);
        $this->insert('resource_type', $exams);
        $this->insert('resource_type', $courseUpdates);
        $this->insert('resource_type', $standards);
        $this->insert('resource_type', $iceBreakers);
    }

}
