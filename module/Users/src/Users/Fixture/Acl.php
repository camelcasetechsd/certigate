<?php

namespace Users\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Users\Entity\Acl as AclEntity;
use Users\Entity\Role as RoleEntity;

/**
 * Acl Fixture
 * 
 * @package users
 * @subpackage fixture
 */
class Acl extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load acl fixture
     * 
     * @access public
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userModule = "Users";
        $userEditRoute = "userEdit";
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

        $resourcesListPerCourseRoute = "resourcesListPerCourse";
        $resourcesResourceDownloadRoute = "resourcesResourceDownload";

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

        $cmsModule = "CMS";
        $cmsPressReleaseSubscribeRoute = "cmsPressReleaseSubscribe";
        
        $acls = array(
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => RoleEntity::PROCTOR_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => RoleEntity::USER_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlinesPdfRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlinesPdfRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlinesPdfRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlinesPdfRoute,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesVoteRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesInstructorTrainingRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $instructorCalendar,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => RoleEntity::USER_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $examBookingRoute,
            ),
            array(
                'role' => RoleEntity::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role' => RoleEntity::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $resourcesListPerCourseRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationsDownloadRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationsDownloadRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationsPendingRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationsPendingRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersListRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersListRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersNewRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersNewRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersEditRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersEditRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersDeleteRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersDeleteRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $listAtcOrgsRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $listAtpOrgsRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $orgTypeRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $orgTypeRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $saveStateRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $saveStateRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $orgEditRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $orgEditRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $orgNewRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $orgNewRoute,
            ),
            array(
                'role' => RoleEntity::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $orgMoreRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $orgMoreRoute,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseEventsDeleteRoute,
                'route' => $courseModule,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseEventsEditRoute,
                'route' => $courseModule,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseEventsNewRoute,
                'route' => $courseModule,
            ),
            array(
                'role' => RoleEntity::TRAINING_MANAGER_ROLE,
                'module' => $courseEventsRoute,
                'route' => $courseModule,
                'role' => RoleEntity::USER_ROLE,
                'module' => $cmsModule,
                'route' => $cmsPressReleaseSubscribeRoute,
            ),
        );

        foreach ($acls as $aclData) {
            $acl = new AclEntity();

            $acl->setModule($aclData["module"])
                    ->setRoute($aclData["route"])
                    ->setRole(
                            $this->getReference($aclData["role"])
            );

            $manager->persist($acl);
        }
        $manager->flush();
    }

    /**
     * Get Fixture order
     * 
     * @access public
     * @return int
     */
    public function getOrder()
    {
        return 2; // number in which order to load fixtures
    }

}
