<?php

require_once __DIR__ . '/../AbstractSeed.php';

use db\AbstractSeed;
use \Users\Entity\Role;
use \Users\Entity\User;

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

        $distributorRole = array(
            'name' => Role::DISTRIBUTOR_ROLE,
            'nameAr' => Role::DISTRIBUTOR_ROLE
        );
        $this->insert('role', $distributorRole);
        $distributorRoleId = $this->getAdapter()->getConnection()->lastInsertId();

        $resellerRole = array(
            'name' => Role::RESELLER_ROLE,
            'nameAr' => Role::RESELLER_ROLE
        );
        $this->insert('role', $resellerRole);
        $resellerRoleId = $this->getAdapter()->getConnection()->lastInsertId();

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


        $chatModule = "Chat";
        $chatRoute = "startChat";
        $minimizeChatRoute = "minimizeChat";
        $chatAcl = array(
            array(
                'role_id' => $normalUserRoleId,
                'module' => $chatModule,
                'route' => $chatRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $chatModule,
                'route' => $minimizeChatRoute,
            ),
        );

        $this->insert('acl', $chatAcl);


        $userModule = "Users";
        $AdminUserCrationRoute = "userCreate";
        $userEditRoute = "userEdit";
        $userInstructorsRoute = "userInstructors";
        $userMoreRoute = "userMore";
        $userAcls = array(
            // ADMIN && TCA && TM only can create users
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $userModule,
                'route' => $AdminUserCrationRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $userModule,
                'route' => $AdminUserCrationRoute,
            ),
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




        $issuesModule = "IssueTracker";
        $issuesListRoute = 'issues';
        $newIssuesRoute = 'newIssues';
        $viewIssuesRoute = 'viewIssues';
        $closeIssuesRoute = 'closeIssues';
        $reopenIssuesRoute = 'reopenIssues';
        $deleteIssuesRoute = 'deleteIssues';
        $editIssueCommentRoute = 'editIssueComment';
        $removeIssueCommentRoute = 'removeIssueComment';

        $IssuesAcls = array(
            array(
                'role_id' => $normalUserRoleId,
                'module' => $issuesModule,
                'route' => $issuesListRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $issuesModule,
                'route' => $newIssuesRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $issuesModule,
                'route' => $viewIssuesRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $issuesModule,
                'route' => $closeIssuesRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $issuesModule,
                'route' => $reopenIssuesRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $issuesModule,
                'route' => $deleteIssuesRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $issuesModule,
                'route' => $editIssueCommentRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $issuesModule,
                'route' => $removeIssueCommentRoute,
            ),
        );
        $this->insert('acl', $IssuesAcls);

        $courseModule = "Courses";
        $coursesCalendarRoute = "coursesCalendar";
        $courseEventsAddCalendarRoute = "courseEventsAddCalendar";
        $courseEventsAlertSubscribeRoute = "courseEventsAlertSubscribe";
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
        $quoteDownloadRoute = "quoteDownload";
        $examRequestsRoute = "examRequests";
        $examProctorsRoute = "examProctors";
        $coursesAcls = array(
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $examProctorsRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $examRequestsRoute,
            ),
            array(
                'role_id' => $normalUserRoleId,
                'module' => $courseModule,
                'route' => $quoteDownloadRoute,
            ),
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
                'route' => $courseEventsAlertSubscribeRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $courseEventsAlertSubscribeRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseEventsAlertSubscribeRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $courseEventsAlertSubscribeRoute,
            ),
            array(
                'role_id' => $studentRoleId,
                'module' => $courseModule,
                'route' => $courseEventsAddCalendarRoute,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $courseModule,
                'route' => $courseEventsAddCalendarRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $courseModule,
                'route' => $courseEventsAddCalendarRoute,
            ),
            array(
                'role_id' => $instructorRoleId,
                'module' => $courseModule,
                'route' => $courseEventsAddCalendarRoute,
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
            // download attachments in pending page
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
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $organizationsDownloadRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $organizationsDownloadRoute,
            ),
            // Pending
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
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $organizationsPendingRoute,
            ),
            array(
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $organizationsPendingRoute,
            ),
            //Organization users List
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
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersRoute,
            ),
            //Organization users List
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
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersListRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersListRoute,
            ),
            // organization user creation
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
                'route' => $organizationUsersNewRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersNewRoute,
            ),
            // organization user edit
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
                'route' => $organizationUsersEditRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersEditRoute,
            ),
            // organization user delete
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
                'route' => $organizationUsersDeleteRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $organizationUsersDeleteRoute,
            ),
            // list atcs routes
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $listAtcOrgsRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $listAtcOrgsRoute,
            ),
            array(
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $listAtcOrgsRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $listAtcOrgsRoute,
            ),
            // list atps
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $listAtpOrgsRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $listAtpOrgsRoute,
            ),
            array(
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $listAtpOrgsRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $listAtpOrgsRoute,
            ),
            // list resellers 
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $listResellerOrgsRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $listResellerOrgsRoute,
            ),
            array(
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $listResellerOrgsRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $listResellerOrgsRoute,
            ),
            // list distributors 
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $listDistOrgsRoute,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $listDistOrgsRoute,
            ),
            array(
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $listDistOrgsRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $listDistOrgsRoute,
            ),
            //organization type
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
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $orgTypeRoute,
            ),
            array(
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $orgTypeRoute,
            ),
            // save state
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
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $saveStateRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $saveStateRoute,
            ),
            // EDIT ORGANIZATION
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
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $orgEditRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $orgEditRoute,
            ),
            // new organization
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
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $orgNewRoute,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $orgNewRoute,
            ),
            // organization More
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
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $orgMoreRoute,
            ),
            array(
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $orgMoreRoute,
            ),
            // renewal 
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $renewal,
            ),
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $renewal,
            ),
            /**
             * Note : distributor && reseller organization cant be renewed 
             * but role added to Acl just to make a descriptive message 
             */
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $renewal,
            ),
            array(
                'role_id' => $resellerRoleId,
                'module' => $organizationModule,
                'route' => $renewal,
            ),
            //my organizations
            array(
                'role_id' => $testCenterAdminRoleId,
                'module' => $organizationModule,
                'route' => $myOrganization,
            ),
            array(
                'role_id' => $trainingManagerRoleId,
                'module' => $organizationModule,
                'route' => $myOrganization,
            ),
            array(
                'role_id' => $distributorRoleId,
                'module' => $organizationModule,
                'route' => $myOrganization,
            ),
            array(
                'role_id' => $resellerRoleId,
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
