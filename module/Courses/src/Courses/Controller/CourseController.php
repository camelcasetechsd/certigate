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
use Zend\Mvc\MvcEvent;

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
            elseif (in_array(Role::TRAINING_MANAGER_ROLE, $storage['roles'])) {
                $trainingManagerId = $storage['id'];
            }
        }
        $criteria = Criteria::create();
        if (!empty($trainingManagerId)) {
            $expr = Criteria::expr();
            $atpsArray = $query->setEntity(/* $entityName = */'Organizations\Entity\Organization')->entityRepository->getOrganizationsBy(/* $userIds = */ array($trainingManagerId));
            $criteria->andWhere($expr->in("atp", $atpsArray));
        }

        $data = $query->filter(/* $entityName = */'Courses\Entity\Course', $criteria);
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');

        $data = $query->findBy(/* $entityName = */'Courses\Entity\Course', /* $criteria = */ array("isForInstructor" => Status::STATUS_INACTIVE, "status" => Status::STATUS_ACTIVE));
        $courseModel->setCanEnroll($data);
        $variables['courses'] = $objectUtilities->prepareForDisplay($data);
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
        $authorizedRoles = array(Role::INSTRUCTOR_ROLE);
        $courseModel->setCanEnroll($data, $authorizedRoles);
        $variables['courses'] = $objectUtilities->prepareForDisplay($data);
        $view = new ViewModel($variables);
        $view->setTemplate('courses/course/calendar');
        return $view;
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
            $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
            $resourceModel = $this->getServiceLocator()->get('Courses\Model\Resource');

            $courseArray = array($course);
            $preparedCourseArray = $courseModel->setCanEnroll($objectUtilities->prepareForDisplay($courseArray));
            $preparedCourse = reset($preparedCourseArray);

            $resources = $preparedCourse->getResources();
            $preparedResources = $resourceModel->prepareResourcesForDisplay($resources);
            $preparedCourse->setResources($preparedResources);

            $variables['course'] = $preparedCourse;
            $variables['evaluation'] = $preparedCourse->getEvaluation();

            $auth = new AuthenticationService();
            $storage = $auth->getIdentity();
            $canDownloadResources = true;
            if ($auth->hasIdentity() && in_array(Role::STUDENT_ROLE, $storage['roles']) && $preparedCourse->canLeave === false) {
                $canDownloadResources = false;
            }

            // check if course has evaluation or not
            $hasEvaluation = false;
            if ($course->getEvaluation() != null) {
                $hasEvaluation = true;
            }

            // check if user is student or admin
            $isStudent = false;
            if ($auth->hasIdentity() && (in_array(Role::STUDENT_ROLE, $storage['roles']) || (in_array(Role::ADMIN_ROLE, $storage['roles'])))) {
                $isStudent = true;
            }
            //check if student already evaluated the course before
            $evaluatedBefore = true;
            if ($isStudent && $course->getEvaluation() != null) {

                $userId = $auth->getIdentity()['id'];
                $courseVotes = $course->getEvaluation()->getVotes();
                foreach ($courseVotes as $vote) {
                    if ($vote->getUser()->getId() == $userId) {
                        $evaluatedBefore = false;
                    }
                }
            }

            $variables['evaluatedBefore'] = $evaluatedBefore;
            $variables['hasEvaluation'] = $hasEvaluation;
            $variables['isStudent'] = $isStudent;
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
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Course');
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $course = new Course();
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;
        $options['userId'] = $storage['id'];
        $form = new CourseForm(/* $name = */ null, $options);
        $form->bind($course, /* $flags = */ FormInterface::VALUES_NORMALIZED, /* $isEditForm = */ false);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($course->getInputFilter());
            $form->setData($data);
            $isCustomValidationValid = $courseModel->validateForm($form, $data);
            if ($form->isValid() && $isCustomValidationValid === true) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $courseModel->save($course, $data, $isAdminUser);

                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array('action' => 'index'), /* $routeName = */ array('name' => "courses"));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['courseForm'] = $this->getFormView($form);
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
        $oldStatus = $course->getStatus();
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity()) {
            if (in_array(Role::ADMIN_ROLE, $storage['roles'])) {
                $isAdminUser = true;
            }
        }



        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;
        $options['userId'] = $storage['id'];
        $form = new CourseForm(/* $name = */ null, $options);
        $form->bind($course);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // bind with empty entity to allow adding new outlines
            $form->bind(new Course());
            $data = $request->getPost()->toArray();
            $form->setInputFilter($course->getInputFilter());

            $form->setData($data);

            $isCustomValidationValid = $courseModel->validateForm($form, $data, $course);
            if ($form->isValid() && $isCustomValidationValid === true) {
                $courseModel->save($course, /* $data = */ array(), $isAdminUser, $oldStatus);

                $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array('action' => 'index'), /* $routeName = */ array('name' => "courses"));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['courseForm'] = $this->getFormView($form);
        return new ViewModel($variables);
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
        $course = $query->find('Courses\Entity\Course', $id);

        $currentUser = $query->find('Users\Entity\User', $storage['id']);

        $notAuthorized = false;
        $routeName = "coursesCalendar";
        if ($course->isForInstructor() === Status::STATUS_ACTIVE) {
            $routeName = "coursesInstructorCalendar";
            if ($auth->hasIdentity() && (!in_array(Role::INSTRUCTOR_ROLE, $storage['roles']))) {
                $notAuthorized = true;
            }
        }
        elseif ($auth->hasIdentity() && ( in_array(Role::INSTRUCTOR_ROLE, $storage['roles']))) {
            $notAuthorized = true;
        }

        if ($notAuthorized === true) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
        }
        else {
            $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
            $courseModel->enrollCourse($course, /* $user = */ $currentUser);
            $url = $this->getEvent()->getRouter()->assemble(/* $params = */ array(), array('name' => $routeName));
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
        $course = $query->find('Courses\Entity\Course', $id);
        $currentUser = $query->find('Users\Entity\User', $storage['id']);

        $routeName = "coursesCalendar";
        if ($course->isForInstructor() === Status::STATUS_ACTIVE) {
            $routeName = "coursesInstructorCalendar";
            if ($auth->hasIdentity() && (!in_array(Role::INSTRUCTOR_ROLE, $storage['roles']))) {
                $notAuthorized = true;
            }
        }
        elseif ($auth->hasIdentity() && ( in_array(Role::INSTRUCTOR_ROLE, $storage['roles']))) {
            $notAuthorized = true;
        }
        if ($notAuthorized === true) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
        }
        else {
            $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
            $courseModel->leaveCourse($course, /* $user = */ $currentUser);
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Evaluation');
        $evalEntity = new \Courses\Entity\Evaluation();
        $evaluationModle = new \Courses\Model\Evaluation($query);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;

        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            // validate questions
            $errors = $evaluationModle->validateQuestion($data['newQuestion']);

            if (empty($errors)) {
                //creating empty user template for this course
                $evalEntity = new \Courses\Entity\Evaluation();
                $evalEntity->setIsTemplate();
                $evalEntity->setIsApproved();
                $evaluationModle->saveEvaluation($evalEntity);
                // save questions
                foreach ($data['newQuestion'] as $new) {
                    $evaluationModle->assignQuestionToEvaluation($new);
                }
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
        $evaluationModel = new \Courses\Model\Evaluation($query);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;

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
                        $evaluationModel->updateQuestion($data['original'][$i], $data['editedQuestion'][$i], $eval->getId());
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Evaluation');
        $courseId = $this->params('courseId');
        $evaluationModle = new \Courses\Model\Evaluation($query);

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
        if ($auth->hasIdentity() && (in_array(array(Role::ADMIN_ROLE), $storage['roles']) || in_array(array(Role::TRAINING_MANAGER_ROLE), $storage['roles']))) {
            $isAdminUser = true;
        }
        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $errors = array();
            // if user didnot edited any of the template questions
            if (isset($data['newQuestion'])) {
                $errors = $evaluationModle->validateQuestion($data['newQuestion']);
            }
            if (empty($errors)) {
                //creating empty user template for this course
                $evalEntity = new \Courses\Entity\Evaluation();
                $evalEntity->setIsUserEval();
                $evalEntity->setIsNotApproved();
                $evaluationModle->saveEvaluation($evalEntity, $courseId);

                // save templates and newQuestions
                foreach ($data['template'] as $temp) {
                    
                    $evaluationModle->assignQuestionToEvaluation($temp, $evalEntity->getId());
                }
                foreach ($data['newQuestion'] as $new) {
                    $evaluationModle->assignQuestionToEvaluation($new, $evalEntity->getId());
                }
                //redirect to course page
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'coursesCalendar'));
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
        // getting course evaluation
        $eval = $course->getEvaluation();
        // getting course evaluation questions
        $variables['oldQuestions'] = $eval->getQuestions();
        // evaluation model
        $evaluationModel = new \Courses\Model\Evaluation($query);

        //authentication
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity() && (in_array(array(Role::ADMIN_ROLE), $storage['roles']) || in_array(array(Role::TRAINING_MANAGER_ROLE), $storage['roles']))) {
            $isAdminUser = true;
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;


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
                        $evaluationModel->updateQuestion($data['original'][$i], $data['editedQuestion'][$i], $eval->getId());
                    }
                }

                //delete deleted questions
                if (isset($data['deleted'])) {
                    foreach ($data['deleted'] as $deletedQuestion) {
                        $evaluationModel->removeQuestion($deletedQuestion);
                    }
                }

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'coursesCalendar'));
                $this->redirect()->toUrl($url);
            }
            else {
                // errors
                $variables['validationError'] = $errors;
                $unValidQuestions = array_merge($data['newQuestion']);
                $variables['unvalidQuestions'] = $unValidQuestions;
            }
        }


        return new ViewModel($variables);
    }

    public function voteAction()
    {
        $variables = array();
        $courseId = $this->params('courseId');
        $query = $this->getServiceLocator()->get("wrapperQuery");
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        // user id
        $id = $auth->getIdentity()['id'];
        //user must be student to see this page 
        if ($auth->hasIdentity() && ( in_array(Role::STUDENT_ROLE, $storage['roles']) || in_array(Role::ADMIN_ROLE, $storage['roles']))) {

            // student must be enrolled in this course
            $course = $query->findOneBy('Courses\Entity\Course', array(
                'id' => $courseId
            ));
            // no course with this id (alert) OR COURSE HAS NO EVALUATION YET
            if ($course == null || $course->getEvaluation() == null) {
                $this->getResponse()->setStatusCode(302);
                $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'resource_not_found'));
                $this->redirect()->toUrl($url);
            }
            // course Exists
            else {
                $enrolledUsers = $course->getUsers();
                $enrolledStudent = null;
                foreach ($enrolledUsers as $user) {
                    if ($user->getId() == $id) {
                        $enrolledStudent = $user;
                    }
                }
                // not enrolled student
                if ($enrolledStudent == null) {
                    $this->getResponse()->setStatusCode(302);
                    $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
                    $this->redirect()->toUrl($url);
                }
                //enrolled student
                else {
                    $questions = $course->getEvaluation()->getQuestions();
                    // questions assosiated with course evaluation
                    $variables['questions'] = $questions;
                    $questionIds = array();
                    foreach ($questions as $question) {
                        array_push($questionIds, $question->getId());
                    }
                }
            }
        }
        else {
            // not a student or admin
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
            $this->redirect()->toUrl($url);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $values = $request->getPost()->toArray();
            $questionModel = new \Courses\Model\Vote($query);
            $questionModel->saveCourseVotes($questionIds, $values, $enrolledStudent, $course->getEvaluation());
            // redirect to course more
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'more', 'id' => $courseId), array('name' => 'coursesMore'));
            $this->redirect()->toUrl($url);
        }
        return new ViewModel($variables);
    }

}
