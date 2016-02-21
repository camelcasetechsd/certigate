<?php

require_once 'init_autoloader.php';
require_once 'module/Users/src/Users/Entity/User.php';
require_once 'module/Users/src/Users/Entity/Role.php';

use Phinx\Seed\AbstractSeed;
use \Users\Entity\User;
use \Users\Entity\Role;

class Users extends AbstractSeed
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

//        $instructorRole = array('name' => Role::INSTRUCTOR_ROLE);
//        $this->insert('role', $instructorRole);
        $instructorRoleId = $this->fetchRow('select id from role where name = "Instructor"')['id'];

        $proctorRole = array('name' => Role::PROCTOR_ROLE);
        $this->insert('role', $proctorRole);
        $proctorRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $studentRole = array('name' => Role::STUDENT_ROLE);
        $this->insert('role', $studentRole);
        $studentRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $testCenterAdminRole = array('name' => Role::TEST_CENTER_ADMIN_ROLE);
        $this->insert('role', $testCenterAdminRole);
        $testCenterAdminRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $trainingManagerRole = array('name' => Role::TRAINING_MANAGER_ROLE);
        $this->insert('role', $trainingManagerRole);
        $trainingManagerRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $userRole = array('name' => Role::USER_ROLE);
        $this->insert('role', $userRole);
        $normalUserRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $adminRole = array('name' => Role::ADMIN_ROLE);
        $this->insert('role', $adminRole);
        $adminRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $userModule = "Users";
        $userEditRoute = "userEdit";
        $userAcls = array(
            array(
                'role_id' => $instructorRoleId,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role_id' => $proctorRoleId,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $userModule,
                'route' => $userEditRoute,
            )
        );
        $this->insert('acl', $userAcls);

        $courseModule = "Courses";
        $coursesCalendarRoute = "coursesCalendar";
        $coursesMoreRoute = "coursesMore";
        $courseOutlines = "courseOutlines";

        $coursesEnrollRoute = "coursesEnroll";
        $coursesLeaveRoute = "coursesLeave";
        $coursesEditRoute = "coursesEdit";
        $coursesNewRoute = "coursesNew";
        $coursesPendingRoute = "coursesPending";
        $coursesListRoute = "courses";
        $coursesVoteRoute = "studentEvaluation";
        $courseEvaluation = "courseEvaluations";
        $newCourseEvaluation = "newCourseEvaluation";
        $editCourseEvaluation = "editCourseEvaluation";

        $instructorCalendar = "coursesInstructorCalendar";
        $coursesInstructorTrainingRoute = "coursesInstructorTraining";
        $examBookingRoute = "examBooking";
        $coursesAcls = array(
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $coursesNewRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $coursesEditRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $coursesListRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $coursesPendingRoute,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $coursesVoteRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseEvaluation,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $newCourseEvaluation,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $editCourseEvaluation,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $coursesInstructorTrainingRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $instructorCalendar,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $examBookingRoute,
            ),
        );
        $this->insert('acl', $coursesAcls);

        $resourcesListPerCourseRoute = "resourcesListPerCourse";
        $resourcesResourceDownloadRoute = "resourcesResourceDownload";
        $resourcesNewPerCourseRoute = "resourcesNewPerCourse";
        $resourcesEditRoute = "resourcesEdit";
        $resourcesAcls = array(
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $resourcesListPerCourseRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $resourcesNewPerCourseRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $resourcesEditRoute,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $resourcesListPerCourseRoute,
            ),
        );
        $this->insert('acl', $resourcesAcls);

        $organizationModule = "Organizations";
        $organizationUsersRoute = "organizationUsers";
        $organizationUsersListRoute = "organizationUsersList";
        $organizationUsersNewRoute = "organizationUsersNew";
        $organizationUsersEditRoute = "organizationUsersEdit";
        $organizationUsersDeleteRoute = "organizationUsersDelete";
        $organizationsPendingRoute = "organizationsPending";
        $organizationsDownloadRoute = "organizationsDownload";
        $listAtcOrgsRoute = "list_atc_orgs";
        $listAtpOrgsRoute = "list_atp_orgs";
        $orgTypeRoute = "org_type";
        $orgMoreRoute = "more";
        $orgNewRoute = "new_org";
        $orgEditRoute = "edit_org";
        $saveStateRoute = "saveState";
        $organizationAcls = array(
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $organizationsDownloadRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationsDownloadRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $organizationsPendingRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationsPendingRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersListRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersListRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersNewRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersNewRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersEditRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersEditRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersDeleteRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersDeleteRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $listAtcOrgsRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $listAtpOrgsRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $orgTypeRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $orgTypeRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $saveStateRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $saveStateRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $orgEditRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $orgEditRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $orgNewRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $orgNewRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $orgMoreRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $orgMoreRoute,
            ),
        );
        $this->insert('acl', $organizationAcls);

        $adminUser = array(
            "firstName" => $faker->firstName,
            "middleName" => $faker->name,
            "lastName" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "admin",
            "password" => User::hashPassword("adminadmin"),
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
        $this->insert('user', $adminUser);
        $adminUserId = $this->getAdapter()->getConnection()->lastInsertId();
        $normalUser = array(
            "firstName" => $faker->firstName,
            "middleName" => $faker->name,
            "lastName" => $faker->lastName,
            "country" => $faker->countryCode,
            "language" => $faker->languageCode,
            "username" => "user",
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
            "instructorStatement" => false,
            "testCenterAdministratorStatement" => false,
            "trainingManagerStatement" => false,
            "status" => true
        );
        $this->insert('user', $normalUser);
        $normalUserId = $this->getAdapter()->getConnection()->lastInsertId();

        $userRoles = array(array(
                'user_id' => $adminUserId,
                'role_id' => $adminRoleId
            ), array(
                'user_id' => $normalUserId,
                'role_id' => $normalUserRoleId
        ));
        $this->insert('user_role', $userRoles);
    }

}
