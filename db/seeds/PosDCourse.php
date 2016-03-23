<?php

require_once __DIR__.'/../AbstractSeed.php';

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

        // dummy user to use his id ad foreign key in orgs
        $normalUser = array(
            "firstName" => $faker->firstName,
            "middleName" => $faker->name,
            "lastName" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "newstudent",
            "password" => "student",
            "mobile" => $faker->phoneNumber,
            "addressOne" => $faker->address,
            "addressTwo" => $faker->address,
            "city" => $faker->city,
            "zipCode" => $faker->postcode,
            "phone" => $faker->phoneNumber,
            "nationality" => $faker->countryCode,
            "identificationType" => $faker->word,
            "identificationNumber" => $faker->numberBetween(/* $min = */ 999999),
            "identificationExpiryDate" => $faker->dateTimeBetween(/* $startDate = */ '+2 years', /* $endDate = */ '+20 years')->format(Time::DATE_FORMAT),
            "email" => $faker->freeEmail,
            "securityQuestion" => $faker->sentence,
            "securityAnswer" => $faker->sentence,
            "dateOfBirth" => date(Time::DATE_FORMAT),
            "photo" => '/upload/images/userdefault.png',
            "privacyStatement" => true,
            "studentStatement" => false,
            "proctorStatement" => false,
            "instructorStatement" => false,
            "testCenterAdministratorStatement" => false,
            "trainingManagerStatement" => false,
            "status" => Status::STATUS_ACTIVE
        );
        $userModel = $this->serviceManager->get("Users\Model\User");
        $userModel->saveUser($normalUser, $userObj = new User(), /*$isAdminUser =*/ true, /*$editFormFlag =*/ false);
        $normalUserId = $userObj->getId();

        // dummy atp to be used in course creation
        $atp = array(
            'commercialName' => $faker->userName,
            'status' => Status::STATUS_ACTIVE,
            'type' => 2,
            'ownerName' => $faker->userName,
            'ownerNationalId' => $faker->randomNumber(),
            'longtitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
            'CRNo' => $faker->randomNumber(),
            'CRExpiration' => date(Time::DATE_FORMAT),
            'CRAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'phone3' => $faker->phoneNumber,
            'fax' => $faker->randomNumber(),
            'website' => $faker->url,
            'email' => $faker->email,
            'addressLine1' => $faker->address,
            'addressLine2' => $faker->address,
            'city' => $faker->city,
            'zipCode' => $faker->randomNumber(),
            //AtpData
            'atpLicenseNo' => $faker->randomNumber(),
            'atpLicenseExpiration' => date(Time::DATE_FORMAT),
            'atpLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'classesNo' => $faker->randomDigitNotNull,
            'pcsNo_class' => $faker->randomDigitNotNull,
            //atcData should be null
            'atcLicenseNo' => null,
            'atcLicenseExpiration' => null,
            'atcLicenseAttachment' => 'public/upload/attachments/crAttachments/1481954966569cc429ba594538397168ff703afaeed43172867529e3c1929a39_2016.01.18_10:53:29am.docx',
            'labsNo' => null,
            'pcsNo_lab' => null,
            'operatingSystem' => null,
            'operatingSystemLang' => null,
            'internetSpeed_lab' => null,
            'officeLang' => null,
            'officeVersion' => null,
            'focalContactPerson' => $normalUserId,
            'creatorId' => $normalUserId,
            'trainingManager_id' => 0,
            'testCenterAdmin_id' => 0,
        );
        $this->serviceManager->get("Organizations\Model\Organization")->saveOrganization($atp, $orgObj = new Organization(), /*$oldStatus =*/ null, /*$creatorId =*/ $normalUserId, /*$userEmail =*/ null, /*$isAdminUser =*/ true, /*$saveState =*/ false);
        $atpId = $orgObj->getId();


        // getting authorized Instuctor role id 
        $instructorRole = $this->serviceManager->get("wrapperQuery")->findOneBy("Users\Entity\Role", array("name" => Role::INSTRUCTOR_ROLE));
        $instructorRoleId = $instructorRole->getId();
        
        $instructor = array(
            "firstName" => $faker->firstName,
            "middleName" => $faker->name,
            "lastName" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "instructor",
            "password" => "useruser",
            "mobile" => $faker->phoneNumber,
            "addressOne" => $faker->address,
            "addressTwo" => $faker->address,
            "city" => $faker->city,
            "zipCode" => $faker->postcode,
            "phone" => $faker->phoneNumber,
            "nationality" => $faker->countryCode,
            "identificationType" => $faker->word,
            "identificationNumber" => $faker->numberBetween(/* $min = */ 999999),
            "identificationExpiryDate" => $faker->dateTimeBetween(/* $startDate = */ '+2 years', /* $endDate = */ '+20 years')->format(Time::DATE_FORMAT),
            "email" => $faker->freeEmail,
            "securityQuestion" => $faker->sentence,
            "securityAnswer" => $faker->sentence,
            "dateOfBirth" => date(Time::DATE_FORMAT),
            "photo" => '/upload/images/userdefault.png',
            "privacyStatement" => true,
            "studentStatement" => false,
            "proctorStatement" => false,
            "instructorStatement" => true,
            "testCenterAdministratorStatement" => false,
            "trainingManagerStatement" => false,
            "status" => Status::STATUS_ACTIVE
        );
        $userModel->saveUser($instructor, $userObj = new User(), /*$isAdminUser =*/ true, /*$editFormFlag =*/ false);
        $instructorId = $userObj->getId();

        $userRoles = array(array(
                'user_id' => $instructorId,
                'role_id' => $instructorRoleId
        ));
        $this->insert('user_role', $userRoles);




        $courseData = array(
            "name" => $faker->firstName,
            "brief" => $faker->text,
            "time" => "10:30",
            "duration" => 20,
            "isForInstructor" => 0,
            "status" => Status::STATUS_ACTIVE,
            "price" => 1,
            "buttons" => array(
                FormButtons::SAVE_AND_PUBLISH_BUTTON => FormButtons::SAVE_AND_PUBLISH_BUTTON_TEXT
            ),
        );
        $this->serviceManager->get("Courses\Model\Course")->save($course = new CourseEntity(), $courseData, /*$editFlag =*/ false, /*$isAdminUser =*/ true);
        $courseId = $course->getId();

        $startDate = new \DateTime("next week");
        $endDate = new \DateTime("next month");
        $courseEventData = array(
            "course" => $courseId,
            "startDate" => $startDate->format(Time::DATE_FORMAT),
            "endDate" => $endDate->format(Time::DATE_FORMAT),
            "capacity" => 100,
            "studentsNo" => 10,
            "atp" => $atpId,
            "ai" => $instructorId,
            "status" => Status::STATUS_ACTIVE,
        );
        $this->serviceManager->get("Courses\Model\CourseEvent")->save(/*$courseEvent =*/ new CourseEventEntity(), $courseEventData);

        // creating outlines for the course
        $outline1 = array(
            "title" => "outline1",
            "course_id" => $courseId,
            "duration" => 10,
            "status" => Status::STATUS_ACTIVE,
            "created" => date('Y-m-d H:i:s'),
            "modified" => null,
        );
        $this->insert('outline', $outline1);

        $outline2 = array(
            "title" => "outline2",
            "course_id" => $courseId,
            "duration" => 10,
            "status" => Status::STATUS_ACTIVE,
            "created" => date('Y-m-d H:i:s'),
            "modified" => null,
        );
        $this->insert('outline', $outline2);
    }

}
