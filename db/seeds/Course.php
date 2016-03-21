<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;
use \Users\Entity\User as User;
use \Users\Entity\Role;

class Course extends AbstractSeed
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
            "username" => "marcoPolo",
            "password" => User::hashPassword("marcoPolo"),
            "mobile" => $faker->phoneNumber,
            "addressOne" => $faker->address,
            "addressTwo" => $faker->address,
            "city" => $faker->city,
            "zipCode" => $faker->postcode,
            "phone" => $faker->phoneNumber,
            "nationality" => $faker->countryCode,
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


        // dummy atp to be used in course creation
        $atp[] = array(
            'commercialName' => $faker->userName,
            'status' => true,
            'type' => 2,
            'ownerName' => $faker->userName,
            'ownerNationalId' => $faker->randomNumber(),
            'longtitude' => $faker->randomFloat(),
            'latitude' => $faker->randomFloat(),
            'CRNo' => $faker->randomNumber(),
            'CRExpiration' => date('Y-m-d H:i:s'),
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
            'atpLicenseExpiration' => date('Y-m-d H:i:s'),
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
            'focalContactPerson_id' => $normalUserId
        );

        $this->insert('organization', $atp);
        $atpId = $this->getAdapter()->getConnection()->lastInsertId();


        // getting authorized Instuctor role id 

        $instructorRole = array(
            'name' => Role::INSTRUCTOR_ROLE,
            'nameAr' => Role::INSTRUCTOR_ROLE
        );
        $this->insert('role', $instructorRole);
        $instructorRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $instructor = array(
            "firstName" => $faker->firstName,
            "middleName" => $faker->name,
            "lastName" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "instructor",
            "password" => User::hashPassword("useruser"),
            "mobile" => $faker->phoneNumber,
            "addressOne" => $faker->address,
            "addressTwo" => $faker->address,
            "city" => $faker->city,
            "zipCode" => $faker->postcode,
            "phone" => $faker->phoneNumber,
            "nationality" => $faker->countryCode,
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
            "instructorStatement" => true,
            "testCenterAdministratorStatement" => false,
            "trainingManagerStatement" => false,
            "status" => true
        );

        $this->insert('user', $instructor);
        $instructorId = $this->getAdapter()->getConnection()->lastInsertId();

        $userRoles = array(array(
                'user_id' => $instructorId,
                'role_id' => $instructorRoleId
        ));
        $this->insert('user_role', $userRoles);




        $course = array(
            "name" => $faker->firstName,
            "startDate" => date('Y-m-d H:i:s'),
            "endDate" => date('Y-m-d H:i:s'),
            "capacity" => 100,
            "studentsNo" => 10,
            "brief" => $faker->text,
            "time" => $faker->date('Y-m-d H:i:s'),
            "duration" => 20,
            "isForInstructor" => 0,
            "status" => 1,
            "created" => date('Y-m-d H:i:s'),
            "modified" => null,
            "atp_id" => $atpId,
            "ai_id" => $instructorId
        );
        $this->insert('course', $course);
        $courseId = $this->getAdapter()->getConnection()->lastInsertId();

        // creating outlines for the course
        $outline1 = array(
            "title" => "outline1",
            "course_id" => $courseId,
            "duration" => 10,
            "status" => 1,
            "created" => date('Y-m-d H:i:s'),
            "modified" => null,
        );
        $this->insert('outline', $outline1);

        $outline2 = array(
            "title" => "outline2",
            "course_id" => $courseId,
            "duration" => 10,
            "status" => 1,
            "created" => date('Y-m-d H:i:s'),
            "modified" => null,
        );
        $this->insert('outline', $outline2);
    }

}
