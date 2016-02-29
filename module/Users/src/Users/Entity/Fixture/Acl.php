<?php

namespace Users\Entity\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Users\Entity\Acl as AclEntity;
use Users\Entity\Role;

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

        $resourcesListPerCourseRoute = "resourcesListPerCourse";
        $resourcesResourceDownloadRoute = "resourcesResourceDownload";
        $resourcesNewPerCourseRoute = "resourcesNewPerCourse";
        $resourcesEditRoute = "resourcesEdit";

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

        $acls = array(
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => Role::PROCTOR_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => Role::STUDENT_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => Role::USER_ROLE,
                'module' => $userModule,
                'route' => $userEditRoute,
            ),
            array(
                'role' => Role::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesCalendarRoute,
            ),
            array(
                'role' => Role::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesMoreRoute,
            ),
            array(
                'role' => Role::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesEnrollRoute,
            ),
            array(
                'role' => Role::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesLeaveRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesNewRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesEditRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesListRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $coursesPendingRoute,
            ),
            array(
                'role' => Role::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $coursesVoteRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $courseEvaluation,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $newCourseEvaluation,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $editCourseEvaluation,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $coursesInstructorTrainingRoute,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $instructorCalendar,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => Role::USER_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => Role::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $courseOutlines,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $examBookingRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $resourcesListPerCourseRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $resourcesNewPerCourseRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $resourcesEditRoute,
            ),
            array(
                'role' => Role::STUDENT_ROLE,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $resourcesResourceDownloadRoute,
            ),
            array(
                'role' => Role::INSTRUCTOR_ROLE,
                'module' => $courseModule,
                'route' => $resourcesListPerCourseRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationsDownloadRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationsDownloadRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationsPendingRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationsPendingRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersListRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersListRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersNewRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersNewRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersEditRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersEditRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersDeleteRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $organizationUsersDeleteRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $listAtcOrgsRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $listAtpOrgsRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $orgTypeRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $orgTypeRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $saveStateRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $saveStateRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $orgEditRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $orgEditRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $orgNewRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $orgNewRoute,
            ),
            array(
                'role' => Role::TEST_CENTER_ADMIN_ROLE,
                'module' => $organizationModule,
                'route' => $orgMoreRoute,
            ),
            array(
                'role' => Role::TRAINING_MANAGER_ROLE,
                'module' => $organizationModule,
                'route' => $orgMoreRoute,
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
