<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;
use \Users\Entity\Role;

class PosARoles extends AbstractSeed
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

        $instructorRole = array('name' => Role::INSTRUCTOR_ROLE, 'nameAr' => Role::INSTRUCTOR_ROLE);
        $this->insert('role', $instructorRole);
        $instructorRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $proctorRole = array(
            'name' => Role::PROCTOR_ROLE,
            'nameAr' => Role::PROCTOR_ROLE
        );
        $this->insert('role', $proctorRole);
        $proctorRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $studentRole = array(
            'name' => Role::STUDENT_ROLE,
            'nameAr' => Role::STUDENT_ROLE
        );
        $this->insert('role', $studentRole);
        $studentRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $testCenterAdminRole = array(
            'name' => Role::TEST_CENTER_ADMIN_ROLE,
            'nameAr' => Role::TEST_CENTER_ADMIN_ROLE
        );
        $this->insert('role', $testCenterAdminRole);
        $testCenterAdminRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $trainingManagerRole = array(
            'name' => Role::TRAINING_MANAGER_ROLE,
            'nameAr' => Role::TRAINING_MANAGER_ROLE
        );
        $this->insert('role', $trainingManagerRole);
        $trainingManagerRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $userRole = array(
            'name' => Role::USER_ROLE,
            'nameAr' => Role::USER_ROLE
        );
        $this->insert('role', $userRole);
        $normalUserRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $adminRole = array(
            'name' => Role::ADMIN_ROLE,
            'nameAr' => Role::ADMIN_ROLE
        );
        $this->insert('role', $adminRole);

        $userModule = "Users";
        $userEditRoute = "userEdit";
        $userInstructorsRoute = "userInstructors";
        $userMoreRoute = "userMore";
        $userAcls = array(
            array(
                'role_id' => $normalUserRoleId,
                'module' => $userModule,
                'route' => $userInstructorsRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $userModule,
                'route' => $userMoreRoute,
            ),
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
        $courseOutlinesPdfRoute = "courseOutlinesPdf";
        $coursesMoreRoute = "coursesMore";
        $courseOutlines = "courseOutlines";
        $coursesEnrollRoute = "coursesEnroll";
        $coursesLeaveRoute = "coursesLeave";
        $coursesVoteRoute = "studentEvaluation";
        $courseEventsRoute = "courseEvents";
        $courseEventsNewRoute = "courseEventsNew";
        $courseEventsEditRoute = "courseEventsEdit";
        $courseEventsDeleteRoute = "courseEventsDelete";
        $instructorCalendar = "coursesInstructorCalendar";
        $coursesInstructorTrainingRoute = "coursesInstructorTraining";
        $examBookingRoute = "examBooking";
        $myCoursesRoute = "myCourses";
        $quoteRoute = "quote";
        $quoteTrainingRoute = "quoteTraining";
        $quoteProcessRoute = "quoteProcess";
        $quoteDeleteRoute = "quoteDelete";
        $coursesAcls = array(
            array(
                'role_id' => $normalUserRoleId,
                'module' => $courseModule,
                'route' => $quoteDeleteRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $courseModule,
                'route' => $quoteProcessRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $courseModule,
                'route' => $quoteTrainingRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $courseModule,
                'route' => $quoteRoute,
            ),
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
                'route' => $courseOutlinesPdfRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $courseOutlinesPdfRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseOutlinesPdfRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $courseOutlinesPdfRoute,
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
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $coursesVoteRoute,
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
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseEventsDeleteRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseEventsEditRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseEventsNewRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseEventsRoute,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $myCoursesRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $myCoursesRoute,
            ),
        );
        $this->insert('acl', $coursesAcls);

        $resourcesListPerCourseRoute = "resourcesListPerCourse";
        $resourcesResourceDownloadRoute = "resourcesResourceDownload";
        $resourcesAcls = array(
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
        $listDistOrgsRoute = "list_distributor_orgs";
        $listResellerOrgsRoute = "list_reseller_orgs";
        $orgTypeRoute = "org_type";
        $orgMoreRoute = "more";
        $orgNewRoute = "new_org";
        $orgEditRoute = "edit_org";
        $saveStateRoute = "saveState";
        $myOrganization = "myOrganizations";
        $renewal = "renew";
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
                'role_id' => $normalUserRoleId,
                'module' => $organizationModule,
                'route' => $listResellerOrgsRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $organizationModule,
                'route' => $listDistOrgsRoute,
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
            array(
                'role_id' => $normalUserRoleId,
                'module' => $organizationModule,
                'route' => $renewal,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $organizationModule,
                'route' => $myOrganization,
            ),
        );
        $this->insert('acl', $organizationAcls);

        $cmsModule = "CMS";
        $cmsPressReleaseSubscribeRoute = "cmsPressReleaseSubscribe";
        $cmsAcls = array(
            array(
                'role_id' => $normalUserRoleId,
                'module' => $cmsModule,
                'route' => $cmsPressReleaseSubscribeRoute,
            ),
        );
        $this->insert('acl', $cmsAcls);
    }

}
