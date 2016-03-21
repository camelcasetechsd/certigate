<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Courses\Form\CourseForm;
use Courses\Entity\Course;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;
use Zend\Form\FormInterface;
use Doctrine\Common\Collections\Criteria;
use Utilities\Service\MessageTypes;

/**
 * Course Controller
 * 
 * courses entries listing
 * 
 * 
 * 
 * @package courses
 * @subpackage controller
 */
class CourseController extends ActionController
{

    /**
     * List courses
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
        }
        $data = $query->findAll(/* $entityName = */'Courses\Entity\Course');
        $variables['courses'] = $objectUtilities->prepareForDisplay($data);
        $variables['isAdminUser'] = $isAdminUser;
        return new ViewModel($variables);
    }

    /**
     * Calendar courses
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function calendarAction()
    {
        $variables = array();
        $token = $this->params('token');
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
        $courseEventModel->approveEnroll($token);

        $pageNumber = $this->getRequest()->getQuery('page');
        $courseModel->filterCourses(/* $criteria = */ array("isForInstructor" => Status::STATUS_INACTIVE, "status" => Status::STATUS_ACTIVE));
        $courseModel->setPage($pageNumber);
        $pageNumbers = $courseModel->getPagesRange($pageNumber);
        $nextPageNumber = $courseModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $courseModel->getPreviousPageNumber($pageNumber);
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;

        $variables['courses'] = $courseEventModel->setCourseEventsPrivileges($courseModel->getCurrentItems());
        return new ViewModel($variables);
    }

    /**
     * Instructor Calendar courses
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function instructorCalendarAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $auth = new AuthenticationService();
        $userId = $auth->getIdentity()["id"];
        $instructorCourseEvents = $query->findBy('Courses\Entity\CourseEvent', /* $criteria = */ array('ai' => $userId), /* $orderBy = */ array('id' => Criteria::DESC));
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $variables['courseEvents'] = $objectUtilities->prepareForDisplay($instructorCourseEvents);
        return new ViewModel($variables);
    }

    /**
     * Instructor Training course
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function instructorTrainingAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');

        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->setMaxResults($maxResults = 1)
                ->orderBy(array("id" => Criteria::DESC))
                ->andWhere($expr->eq("status", Status::STATUS_ACTIVE))
                ->andWhere($expr->eq("isForInstructor", Status::STATUS_ACTIVE));
        $data = $query->filter(/* $entityName = */'Courses\Entity\Course', $criteria);

        if (count($data) == 0) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'resource_not_found'));
            $this->redirect()->toUrl($url);
        }
        else {

            $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');
            $preparedCourseArray = $courseModel->setCourseEventsPrivileges($objectUtilities->prepareForDisplay($data));
            $preparedCourse = reset($preparedCourseArray);

            $resources = $preparedCourse->getResources();
            $preparedResources = $resourceModel->prepareResourcesForDisplay($resources);
            $preparedCourse->setResources($preparedResources);

            $variables['course'] = $preparedCourse;

            $auth = new AuthenticationService();
            $storage = $auth->getIdentity();
            $canDownloadResources = true;
            if ($auth->hasIdentity() && in_array(Role::STUDENT_ROLE, $storage['roles']) && $preparedCourse->canLeave === false) {
                $canDownloadResources = false;
            }

            $variables['canDownloadResources'] = $canDownloadResources;


            return new ViewModel($variables);
        }
    }

    /**
     * More course
     *
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function moreAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $course = $query->find('Courses\Entity\Course', $id);
        if ($course != null) {
            $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
            $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');

            $courseArray = array($course);

            $preparedCourseArray = $courseEventModel->setCourseEventsPrivileges($objectUtilities->prepareForDisplay($courseArray));
            $preparedCourse = reset($preparedCourseArray);

            $outlines = $preparedCourse->getOutlines();
            $preparedOutlines = $objectUtilities->prepareForDisplay($outlines);
            $preparedCourse->setOutlines($preparedOutlines);

            $resources = $preparedCourse->getResources();
            $preparedResources = $resourceModel->prepareResourcesForDisplay($resources);
            $preparedCourse->setResources($preparedResources);

            $variables['course'] = $preparedCourse;

            $auth = new AuthenticationService();
            $storage = $auth->getIdentity();
            $canDownloadResources = true;
            if ($auth->hasIdentity() && $preparedCourse->canDownload === false && in_array(Role::STUDENT_ROLE, $storage['roles'])) {
                $canDownloadResources = false;
            }

            $variables['canEvaluate'] = $preparedCourse->canEvaluate;
            $variables['courseEventId'] = $this->params('courseEventId');
            $variables['canDownloadResources'] = $canDownloadResources;
        }
        // if course does not exist
        else {

            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'resource_not_found'));
            $this->redirect()->toUrl($url);
        }

        return new ViewModel($variables);
    }

    /**
     * Create new course
     * 
     * 
     * @access public
     * @uses Course
     * @uses CourseForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $course = new Course();
        // setting default isForInstructor
        $course->setIsForInstructor(Status::STATUS_INACTIVE);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        $userEmail = null;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            $userEmail = $storage['email'];
        }

        $options = array();
        $options['query'] = $query;
        $options['userId'] = $storage['id'];
        $form = new CourseForm(/* $name = */ null, $options);
        $form->bind($course, /* $flags = */ FormInterface::VALUES_NORMALIZED, /* $isEditForm = */ false);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($course->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $courseModel->save($course, $data, /* $editFlag = */ false, $isAdminUser, $userEmail);

                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array('action' => 'index'), /* $routeName = */ array('name' => "courses"));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['courseForm'] = $this->getFormView($form);
        $variables['isAdminUser'] = $isAdminUser;
        return new ViewModel($variables);
    }

    /**
     * Edit course
     * 
     * 
     * @access public
     * @uses CourseForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $course = $query->find('Courses\Entity\Course', $id);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        $userEmail = null;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            $userEmail = $storage["email"];
        }

        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }
        $options = array();
        $options['query'] = $query;
        $form = new CourseForm(/* $name = */ null, $options);
        $form->bind($course);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // bind with empty entity to allow adding new outlines
            $form->bind(new Course());
            $data = $request->getPost()->toArray();
            $form->setInputFilter($course->getInputFilter());

            $form->setData($data);
            if ($form->isValid()) {
                $courseModel->save($course, $data, /* $editFlag = */ true, $isAdminUser, $userEmail);

                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array('action' => 'edit', 'id' => $id), /* $routeName = */ array('name' => "coursesEdit"));
                $this->redirect()->toUrl($url);
            }
        }
        $entitiesAndLogEntriesArray = $courseModel->getLogEntries($course);

        $variables['courseId'] = $id;
        $variables['courseForm'] = $this->getFormView($form);
        $variables['isAdminUser'] = $isAdminUser;
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $pendingUrl = $this->getEvent()->getRouter()->assemble(array('id' => $id), array('name' => 'coursesPending'));
        $hasPendingChanges = $entitiesAndLogEntriesArray["hasPendingChanges"];
        $variables['messages'] = $versionModel->getPendingMessages($hasPendingChanges, $pendingUrl);
        return new ViewModel($variables);
    }

    /**
     * View pending version course
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function pendingAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $course = $query->find('Courses\Entity\Course', $id);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $entitiesAndLogEntriesArray = $courseModel->getLogEntries($course);
        $courseArray = $entitiesAndLogEntriesArray["course"];
        $courseLogs = $entitiesAndLogEntriesArray["courseLogs"];
        $courseComparisonData = $versionModel->prepareDiffs($courseArray, $courseLogs);

        $outlines = $entitiesAndLogEntriesArray["outlines"];
        $outlinesLogs = $entitiesAndLogEntriesArray["outlinesLogs"];
        $outlinesComparisonData = $versionModel->prepareDiffs($outlines, $outlinesLogs);

        $resources = $entitiesAndLogEntriesArray["resources"];
        $resourcesLogs = $entitiesAndLogEntriesArray["resourcesLogs"];
        $resourcesComparisonData = $versionModel->prepareDiffs($resources, $resourcesLogs);

        $questions = $entitiesAndLogEntriesArray["questions"];
        $questionsLogs = $entitiesAndLogEntriesArray["questionsLogs"];
        $questionsComparisonData = $versionModel->prepareDiffs($questions, $questionsLogs);

        $variables['course'] = $courseComparisonData;
        $variables['outlines'] = $outlinesComparisonData;
        $variables['resources'] = $resourcesComparisonData;
        $variables['questions'] = $questionsComparisonData;
        $variables['isAdminUser'] = $isAdminUser;
        $variables['id'] = $id;
        return new ViewModel($variables);
    }

    /**
     * Approve pending version course
     * 
     * 
     * @access public
     */
    public function approveAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $course = $query->find('Courses\Entity\Course', $id);

        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $entitiesAndLogEntriesArray = $courseModel->getLogEntries($course);
        $courseArray = $entitiesAndLogEntriesArray["course"];
        $courseLogs = $entitiesAndLogEntriesArray["courseLogs"];
        $outlines = $entitiesAndLogEntriesArray["outlines"];
        $outlinesLogs = $entitiesAndLogEntriesArray["outlinesLogs"];
        $resources = $entitiesAndLogEntriesArray["resources"];
        $resourcesLogs = $entitiesAndLogEntriesArray["resourcesLogs"];
        $evaluationArray = $entitiesAndLogEntriesArray["evaluation"];
        $evaluationLogs = $entitiesAndLogEntriesArray["evaluationLogs"];
        $questions = $entitiesAndLogEntriesArray["questions"];
        $questionsLogs = $entitiesAndLogEntriesArray["questionsLogs"];

        $versionModel->approveChanges($courseArray, $courseLogs);
        $versionModel->approveChanges($outlines, $outlinesLogs);
        $versionModel->approveChanges($resources, $resourcesLogs);
        $versionModel->approveChanges($questions, $questionsLogs);
        $versionModel->approveChanges($evaluationArray, $evaluationLogs);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
        $this->redirect()->toUrl($url);
    }

    /**
     * Disapprove pending version course
     * 
     * 
     * @access public
     */
    public function disapproveAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $course = $query->find('Courses\Entity\Course', $id);

        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $entitiesAndLogEntriesArray = $courseModel->getLogEntries($course);
        $courseArray = $entitiesAndLogEntriesArray["course"];
        $courseLogs = $entitiesAndLogEntriesArray["courseLogs"];
        $outlines = $entitiesAndLogEntriesArray["outlines"];
        $outlinesLogs = $entitiesAndLogEntriesArray["outlinesLogs"];
        $resources = $entitiesAndLogEntriesArray["resources"];
        $resourcesLogs = $entitiesAndLogEntriesArray["resourcesLogs"];
        $evaluationArray = $entitiesAndLogEntriesArray["evaluation"];
        $evaluationLogs = $entitiesAndLogEntriesArray["evaluationLogs"];
        $questions = $entitiesAndLogEntriesArray["questions"];
        $questionsLogs = $entitiesAndLogEntriesArray["questionsLogs"];

        $versionModel->disapproveChanges($courseArray, $courseLogs);
        $versionModel->disapproveChanges($outlines, $outlinesLogs);
        $versionModel->disapproveChanges($resources, $resourcesLogs);
        $versionModel->disapproveChanges($questions, $questionsLogs);
        $versionModel->disapproveChanges($evaluationArray, $evaluationLogs);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
        $this->redirect()->toUrl($url);
    }

    /**
     * Delete course
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $course = $query->find('Courses\Entity\Course', $id);

        $course->setStatus(Status::STATUS_INACTIVE);

        $query->save($course);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
        $this->redirect()->toUrl($url);
    }

    /**
     * Enroll course
     *
     * 
     * @access public
     */
    public function enrollAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $courseEvent = $query->find('Courses\Entity\CourseEvent', $id);
        $course = $courseEvent->getCourse();

        $currentUser = $query->find('Users\Entity\User', $storage['id']);

        $notAuthorized = false;
        $routeName = "coursesCalendar";
        if ($course->isForInstructor() === Status::STATUS_ACTIVE) {
            $routeName = "coursesInstructorTraining";
            if ($auth->hasIdentity() && !(in_array(Role::INSTRUCTOR_ROLE, $storage['roles']) || in_array(Role::ADMIN_ROLE, $storage['roles']))) {
                $notAuthorized = true;
            }
        }
        if ($auth->hasIdentity() && ( in_array(Role::INSTRUCTOR_ROLE, $storage['roles']) && $storage['id'] == $courseEvent->getAi()->getId())) {
            $notAuthorized = true;
        }

        if ($notAuthorized === true) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
        }
        else {
            $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
            $redirectUrl = $this->getEvent()->getRouter()->assemble(/* $params = */ array(), array('name' => $routeName, 'force_canonical' => true));
            $url = $courseEventModel->enrollCourse($courseEvent, /* $user = */ $currentUser, $redirectUrl);
        }
        $this->redirect()->toUrl($url);
    }

    /**
     * Leave course
     *
     * 
     * @access public
     */
    public function leaveAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $courseEvent = $query->find('Courses\Entity\CourseEvent', $id);
        $course = $courseEvent->getCourse();
        $currentUser = $query->find('Users\Entity\User', $storage['id']);
        $notAuthorized = false;
        $routeName = "coursesCalendar";
        if ($course->isForInstructor() === Status::STATUS_ACTIVE) {
            $routeName = "coursesInstructorTraining";
            if ($auth->hasIdentity() && !(in_array(Role::INSTRUCTOR_ROLE, $storage['roles']) || in_array(Role::ADMIN_ROLE, $storage['roles']))) {
                $notAuthorized = true;
            }
        }
        if ($auth->hasIdentity() && ( in_array(Role::INSTRUCTOR_ROLE, $storage['roles']) && $storage['id'] == $courseEvent->getAi()->getId())) {
            $notAuthorized = true;
        }
        if ($notAuthorized === true) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
        }
        else {
            $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
            $courseEventModel->leaveCourse($courseEvent, /* $user = */ $currentUser);
            $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array(), array('name' => $routeName));
        }
        $this->redirect()->toUrl($url);
    }

    public function evTemplatesAction()
    {
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $evaluation = $query->findOneBy('Courses\Entity\Evaluation', array('isTemplate' => 1));
        if (!$evaluation) {
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'new'), array('name' => 'newEvTemplate'));
            $this->redirect()->toUrl($url);
        }
        else {

            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'edit'), array('name' => 'editEvTemplate'));
            $this->redirect()->toUrl($url . '/' . $evaluation->getId());
        }
    }

    /**
     * This method is meant for creating Evaluation template for the first Time
     * 
     * @return ViewModel
     */
    public function newEvTemplateAction()
    {
        $variables = array();
        $evalEntity = new \Courses\Entity\Evaluation();
        $evaluationModel = $this->getServiceLocator()->get("Courses\Model\Evaluation");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            // validate questions
            $errors = $evaluationModel->validateQuestion($data['newQuestion']);

            if (empty($errors)) {
                //creating empty user template for this course
                $evalEntity = new \Courses\Entity\Evaluation();
                $evalEntity->setIsTemplate();
                $evalEntity->setPercentage(0.00);
                $evaluationModel->saveEvaluation($evalEntity);
                // save questions
                foreach ($data['newQuestion'] as $new) {
                    $evaluationModel->assignQuestionToEvaluation($new);
                }
                //redirect to edit page
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'editEvTemplate', 'id' => $evalEntity->getId()), array('name' => 'editEvTemplate'));
                $this->redirect()->toUrl($url);
            }
            else {
                $variables['validationError'] = $errors;
                // unvalid questions
                $variables['oldQuestions'] = $data['newQuestion'];
            }
        }
        return new ViewModel($variables);
    }

    public function editEvTemplateAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $eval = $query->find('Courses\Entity\Evaluation', $id);
        $evaluationModel = $this->getServiceLocator()->get("Courses\Model\Evaluation");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $error1 = array();
            $error2 = array();
            if (isset($data['editedQuestion'])) {
                $error1 = $evaluationModel->validateQuestion($data['editedQuestion']);
            }
            if (isset($data['newQuestion'])) {
                $error2 = $evaluationModel->validateQuestion($data['newQuestion']);
            }
            $errors = array_merge($error1, $error2);
            if (empty($errors)) {
                // saving new Questions
                if (isset($data['newQuestion'])) {
                    foreach ($data['newQuestion'] as $new) {
                        $evaluationModel->assignQuestionToEvaluation($new, $eval->getId());
                    }
                }
                // updating old questions
                if (isset($data['editedQuestion']) && isset($data['original'])) {
                    for ($i = 0; $i < count($data['editedQuestion']); $i++) {
                        $evaluationModel->updateQuestion($data['original'][$i], $data['editedQuestion'][$i], $eval);
                    }
                }

                //delete deleted questions
                if (isset($data['deleted'])) {
                    foreach ($data['deleted'] as $deletedQuestion) {
                        $evaluationModel->removeQuestion($deletedQuestion);
                    }
                }
            }
            else {

                // errors
                $variables['validationError'] = $errors;

                if (isset($newQuestion)) {
                    $unValidQuestions = array_merge($data['newQuestion']);
                    $variables['unvalidQuestions'] = $unValidQuestions;
                }
            }
        }

        $variables['questions'] = $eval->getQuestions();
        return new ViewModel($variables);
    }

    /**
     * Create new Course evaluation
     * 
     * @return ViewModel
     */
    public function newEvaluationAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $courseId = $this->params('courseId');
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }
        $evaluationModel = $this->getServiceLocator()->get("Courses\Model\Evaluation");

        // getting template evalutaion
        $evaluationTemplate = $query->findOneBy("Courses\Entity\Evaluation", array(
            "isTemplate" => 1
        ));

        if ($evaluationTemplate !== null) {
            // populate form with template questions
            $variables['templateQuestions'] = $evaluationTemplate->getQuestions();
        }
        // authentication
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        // admin or atp only
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            $userEmail = $storage['email'];
        }
        $variables['isAdminUser'] = $isAdminUser;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $errors = array();
            // if user didnot edited any of the template questions
            if (isset($data['newQuestion'])) {
                $errors = $evaluationModel->validateQuestion($data['newQuestion']);
            }
            if (empty($errors)) {
                //creating empty user template for this course
                $evalEntity = new \Courses\Entity\Evaluation();
                $evalEntity->setIsUserEval();
                $evalEntity->setPercentage(0.00);
                $status = Status::STATUS_NOT_APPROVED;
                if ($isAdminUser === true) {
                    $status = isset($data["status"]) ? Status::STATUS_ACTIVE : Status::STATUS_INACTIVE;
                }
                $evalEntity->setStatus($status);
                $evaluationModel->saveEvaluation($evalEntity, $courseId, $userEmail, $isAdminUser, /* $editFlag = */ false);
                // save templates and newQuestions
                foreach ($data['template'] as $temp) {

                    $evaluationModel->assignQuestionToEvaluation($temp, $evalEntity->getId());
                }
                if (isset($data['newQuestion'])) {
                    foreach ($data['newQuestion'] as $new) {
                        $evaluationModel->assignQuestionToEvaluation($new, $evalEntity->getId());
                    }
                }
                //redirect to course page
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'editEvaluation', 'courseId' => $courseId), array('name' => 'editCourseEvaluation'));
                $this->redirect()->toUrl($url);
            }
            else {
                $variables['validationError'] = $errors;
                if (isset($data['newQuestion'])) {
                    // unvalid questions
                    $variables['oldQuestions'] = $data['newQuestion'];
                }
            }
        }
        $variables['courseId'] = $courseId;
        return new ViewModel($variables);
    }

    /**
     * edit course evaluation
     *  
     * @return ViewModel
     */
    public function editEvaluationAction()
    {
        $variables = array();
        $courseId = $this->params('courseId');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        // getting the course
        $course = $query->find('Courses\Entity\Course', $courseId);
        $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE);
        if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
            return $this->redirect()->toUrl($validationResult["redirectUrl"]);
        }
        // getting course evaluation
        $eval = $course->getEvaluation();
        if ($eval == null) {
            $variables['noEvaluation'] = true;
        }
        else {
            $variables['status'] = ($eval->getStatus() === Status::STATUS_ACTIVE) ? true : false;
            // getting course evaluation questions
            $variables['oldQuestions'] = $eval->getQuestions();
            // evaluation model
            $evaluationModel = $this->getServiceLocator()->get("Courses\Model\Evaluation");
        }

        //authentication
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
            $userEmail = $storage['email'];
        }

        $variables['isAdminUser'] = $isAdminUser;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $error1 = array();
            $error2 = array();
            if (isset($data['editedQuestion'])) {
                $error1 = $evaluationModel->validateQuestion($data['editedQuestion']);
            }
            if (isset($data['newQuestion'])) {
                $error2 = $evaluationModel->validateQuestion($data['newQuestion']);
                $errors = array_merge($error1, $error2);
            }
            else {
                $errors = $error1;
            }
            if (empty($errors)) {
                $status = Status::STATUS_NOT_APPROVED;
                if ($isAdminUser === true) {
                    $status = isset($data["status"]) ? Status::STATUS_ACTIVE : Status::STATUS_INACTIVE;
                }
                $eval->setStatus($status);
                $evaluationModel->saveEvaluation($eval, $courseId, $userEmail, $isAdminUser, /* $editFlag = */ true);
                // saving new Questions
                if (isset($data['newQuestion'])) {
                    foreach ($data['newQuestion'] as $new) {
                        $evaluationModel->assignQuestionToEvaluation($new, $eval->getId());
                    }
                }
                // updating old questions
                if (isset($data['editedQuestion']) && isset($data['original'])) {
                    for ($i = 0; $i < count($data['editedQuestion']); $i++) {
                        $evaluationModel->updateQuestion($data['original'][$i], $data['editedQuestion'][$i], $eval);
                    }
                }

                //delete deleted questions
                if (isset($data['deleted']) && $isAdminUser === true) {
                    foreach ($data['deleted'] as $deletedQuestion) {
                        $evaluationModel->removeQuestion($deletedQuestion);
                    }
                }

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'editEvaluation', 'courseId' => $courseId), array('name' => 'editCourseEvaluation'));
                $this->redirect()->toUrl($url);
            }
            else {
                // errors
                $variables['validationError'] = $errors;
                $unValidQuestions = isset($data['newQuestion']) ? $data['newQuestion'] : null;
                $variables['unvalidQuestions'] = $unValidQuestions;
            }
        }
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $entitiesAndLogEntriesArray = $courseModel->getLogEntries($course);

        $variables['courseId'] = $courseId;
        $hasPendingChanges = $entitiesAndLogEntriesArray["hasPendingChanges"];
        $pendingUrl = $this->getEvent()->getRouter()->assemble(array('id' => $courseId), array('name' => 'coursesPending'));
        $versionModel = $this->getServiceLocator()->get('Versioning\Model\Version');
        $variables['messages'] = $versionModel->getPendingMessages($hasPendingChanges, $pendingUrl);
        return new ViewModel($variables);
    }

    public function voteAction()
    {
        $variables = array();
        $courseEventId = $this->params('courseEventId');
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
        $voteModel = $this->getServiceLocator()->get('Courses\Model\Vote');
        $query = $this->getServiceLocator()->get("wrapperQuery");
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $id = $storage['id'];
        $courseEvent = $query->find('Courses\Entity\CourseEvent', $courseEventId);

        // no course with this id (alert)
        if ($courseEvent == null) {
            $redirectRoute = "resource_not_found";
        }
        else {
            $course = $courseEvent->getCourse();
            $preparedCourseArray = $courseEventModel->setCourseEventsPrivileges(array($course));
            $preparedCourse = reset($preparedCourseArray);
            if ($preparedCourse->canEvaluate === false) {
                $redirectRoute = "noaccess";
            }
        }
        if (isset($redirectRoute)) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => $redirectRoute));
            $this->redirect()->toUrl($url);
        }
        $enrolledStudent = $query->find("Users\Entity\User", $id);
        $questionsArray = $course->getEvaluation()->getApprovedQuestions();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $values = $request->getPost()->toArray();
            $voteModel->saveCourseVotes($questionsArray['questionIds'], $values, $enrolledStudent, $course->getEvaluation(), $courseEvent);
            // redirect to course more
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'more', 'id' => $courseEventId), array('name' => 'coursesMore'));
            $this->redirect()->toUrl($url);
        }
        $variables['questions'] = $questionsArray['questions'];
        return new ViewModel($variables);
    }

    public function myCoursesAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get("wrapperQuery");
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $auth = new AuthenticationService();
        // user id
        $id = $auth->getIdentity()['id'];
        $user = $query->findOneBy('Users\Entity\User', array(
            'id' => $id
        ));

        $userCourses = $user->getCourseEvents();
        $courseEventModel = new \Courses\Model\CourseEvent($query, $this->getServiceLocator()->get('objectUtilities'));
        $variables['courseEvents'] = $courseEventModel->prepareCourseOccurrences($userCourses);
        // if user did not enroll in any course
        if (count($userCourses) < 1) {
            $variables['messages'] = array(
                array(
                    'message' => 'Currently you are not enrolled in any courses',
                    'type' => MessageTypes::WARNING
                )
            );
        }

        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $request = $this->getRequest();
        $pageNumber = $this->getRequest()->getQuery('page');
        $courseModel->setPage($pageNumber);

        $pageNumbers = $courseModel->getPagesRange($pageNumber);
        $nextPageNumber = $courseModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $courseModel->getPreviousPageNumber($pageNumber);
        $variables['menuItems'] = $objectUtilities->prepareForDisplay($courseModel->getCurrentItems());
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;
        $variables['filterQuery'] = preg_replace('/page=[\d]+&/i', '', $request->getUri()->getQuery());
        return new ViewModel($variables);
    }

}
