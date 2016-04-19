<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;
use Users\Entity\User;
use Users\Entity\Role;
use Courses\Entity\Course as CourseEntity;
use Courses\Entity\CourseEvent as CourseEventEntity;
use Utilities\Form\FormButtons;
use Utilities\Service\Time;
use Organizations\Entity\Organization;
use Utilities\Service\Status;

class PosDCourse extends AbstractSeed
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

        // dummy atp to be used in course creation
        $atp = $this->serviceManager->get("wrapperQuery")->findOneBy("Organizations\Entity\Organization", array("commercialName" => 'atpDummy'));
        // getting authorized Instuctor role id 
        $instructorUser = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\User", array("username" => 'instructor'));

        $courseData = array(
            "name" => $faker->firstName,
            "nameAr" => $faker->firstName,
            "brief" => $faker->text,
            "briefAr" => $faker->text,
            "time" => $faker->date('H:i:s'),
            "duration" => 20,
            "isForInstructor" => 0,
            "status" => Status::STATUS_ACTIVE,
            "price" => 1,
            "buttons" => array(
                FormButtons::SAVE_AND_PUBLISH_BUTTON => FormButtons::SAVE_AND_PUBLISH_BUTTON_TEXT
            ),
        );
        $this->serviceManager->get("Courses\Model\Course")->save($course = new CourseEntity(), $courseData, /* $editFlag = */ false, /* $isAdminUser = */ true);
        $courseId = $course->getId();

        $startDate = new \DateTime("next week");
        $endDate = new \DateTime("next month");
        $courseEventData = array(
            "course" => $courseId,
            "startDate" => $startDate->format(Time::DATE_FORMAT),
            "startDateHj" => $startDate->format(Time::DATE_FORMAT),
            "endDate" => $endDate->format(Time::DATE_FORMAT),
            "endDateHj" => $endDate->format(Time::DATE_FORMAT),
            "capacity" => 100,
            "studentsNo" => 10,
            "atp" => $atp->getId(),
            "ai" => $instructorUser->getId(),
            "status" => Status::STATUS_ACTIVE,
            "hideFromCalendar" => 0
        );
        $this->serviceManager->get("Courses\Model\CourseEvent")->save(/* $courseEvent = */ new CourseEventEntity(), $courseEventData);

        // creating outlines for the course
        $outline1 = array(
            "title" => "outline1",
            "titleAr" => "outline1",
            "course_id" => $courseId,
            "duration" => 10,
            "status" => Status::STATUS_ACTIVE,
            "created" => date('Y-m-d H:i:s'),
            "modified" => null,
        );
        $this->insert('outline', $outline1);

        $outline2 = array(
            "title" => "outline2",
            "titleAr" => "outline2",
            "course_id" => $courseId,
            "duration" => 10,
            "status" => Status::STATUS_ACTIVE,
            "created" => date('Y-m-d H:i:s'),
            "modified" => null,
        );
        $this->insert('outline', $outline2);
    }

}
