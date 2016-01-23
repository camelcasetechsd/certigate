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
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\I18n\Validator\Alpha;

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
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $data = $query->findAll(/* $entityName = */null);
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

        $data = $query->findBy(/* $entityName = */null, /* $criteria = */ array("status" => Status::STATUS_ACTIVE));
        $courseModel->setCanEnroll($data);
        $variables['courses'] = $objectUtilities->prepareForDisplay($data);
        return new ViewModel($variables);
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
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $course = $query->find('Courses\Entity\Course', $id);
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
        $variables['canDownloadResources'] = $canDownloadResources;
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
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;
        $form = new CourseForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            $form->setInputFilter($course->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $courseModel->save($course, $data, $isAdminUser);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
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
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;
        $form = new CourseForm(/* $name = */ null, $options);
        $form->bind($course);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            $form->setInputFilter($course->getInputFilter());

            $inputFilter = $form->getInputFilter();
            $form->setData($data);
            // file not updated
            if (isset(reset($fileData['presentations'])['name']) && empty(reset($fileData['presentations'])['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('presentations');
                $input->setRequired(false);
            }
            if (isset($fileData['activities']['name']) && empty($fileData['activities']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('activities');
                $input->setRequired(false);
            }
            if (isset($fileData['exams']['name']) && empty($fileData['exams']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('exams');
                $input->setRequired(false);
            }
            if ($form->isValid()) {
                $courseModel->save($course, /* $data = */ array(), $isAdminUser, $oldStatus);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
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
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $courseModel->enrollCourse($course, /* $user = */ $currentUser);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'coursesCalendar'));
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
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $courseModel->leaveCourse($course, /* $user = */ $currentUser);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'coursesCalendar'));
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

            // creating the one and only template Evaluation
            $evalEntity->setIsTemplate();
            $evalEntity->setIsApproved();
            $evaluationModle->saveEvaluation($evalEntity);

            // initialize validation values
            $isStringValid = false;
            $isLengthValid = false;
//            $messages = array();
            //string and length validators
            $stringValidator = new \Zend\I18n\Validator\Alnum(array('allowWhiteSpace' => true));
            $lengthValidator = new \Zend\Validator\StringLength(array('min' => 10));

            //loop over all questions
            foreach ($data['questionTitle'] as $question) {
                // if question does not exist in DB
                if (!$query->checkExistance("Courses\Entity\Question", "questionTitle", $question)) {
                    // start question validation
                    $isStringValid = $stringValidator->isValid($question);
                    $isLengthValid = $lengthValidator->isValid($question);
                    // check if string
                    if (!$isStringValid) {
                        array_push($messages, "Please insert valid questions");
                    }
                    // check on length
                    if (!$isLengthValid) {
                        array_push($messages, "question must not be less than 10 charachters");
                    }
                    /**
                     * we save questions one by one to be able to validate uniquness
                     */
                    if ($isStringValid && $isLengthValid && empty($messages)) {
                        $evaluationModle->assignQuestionToEvaluation($question);
                    }
                }
                else {
                    array_push($messages, "One of your questions already existed.");
                }
            }
            // if there are error
            if (!empty($messages)) {
                $variables['validationError'] = $messages;
            }
            // if there's no error
            else {
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'home'));
                $this->redirect()->toUrl($url);
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
            //delete deleted questions
            if (isset($data['deleted'])) {
                foreach ($data['deleted'] as $deletedQuestion) {
                    $evaluationModel->removeQuestion($deletedQuestion);
                }
            }

            //edit without validation
            if (isset($data['editedQuestion']) && isset($data['original'])) {

                for ($i = 0; $i < count($data['editedQuestion']); $i++) {
                    if (empty($evaluationModel->validateQuestion($data['editedQuestion'][$i]))) {
                        $evaluationModel->updateQuestion($data['original'][$i], $data['editedQuestion'][$i]);
                    }
                }
            }

            //insert without validation
            if (isset($data['newQuestion'])) {
                foreach ($data['newQuestion'] as $new) {
                    $evaluationModel->assignQuestionToEvaluation($new);
                }
            }
        }

        $variables['questions'] = $eval->getQuestions();
        return new ViewModel($variables);
    }

    /**
     * List Course Evaluations (admin's default evaluation templates & atp's course evaluations)
     * 
     * 
     * @return ViewModel
     */
//    public function evaluationAction()
//    {
//        $variables = array();
//        $courseId = $this->params('courseId');
//        $query = $this->getServiceLocator()->get('wrapperQuery');
//        $course = $query->find('Courses\Entity\Course', $courseId);
//
//        $eval = $course->getEvaluation();
//
//        $questions = $eval->getQuestions();
//
//        $variables['questions'] = $questions;
//        return new ViewModel($variables);
//    }

    /**
     * Create new Course evaluation
     * 
     * @return ViewModel
     */
    public function newEvaluationAction()
    {
        $variables = array();
        $courseId = $this->params('courseId');

        /*         * *         * *
         * need to get admin template if exist and set it with the questions
         */

        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\Evaluation');
        $evalEntity = new \Courses\Entity\Evaluation();
        $evaluationModle = new \Courses\Model\Evaluation($query);

        // authentication
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        // admin or atp only
        if ($auth->hasIdentity() && in_array(array(Role::ADMIN_ROLE, Role::TRAINING_MANAGER_ROLE), $storage['roles'])) {
            $isAdminUser = true;
        }
        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $request->getPost()->toArray();

            $messages = array();
            //loop over all questions
            foreach ($data['questionTitle'] as $question) {
                // if question does not exist in DB
                if ($evaluationModle->checkQuestionExistanceInEvalautaion($evalEntity, $question)) {

                    $errors = $evaluationModle->validateQuestion($question);
                    if (empty($errors)) {
                        //creating empty user template for this course
                        $evalEntity->setIsUserEval();
                        $evalEntity->setIsNotApproved();
                        $evaluationModle->saveEvaluation($evalEntity, $courseId);
                        //assign a question to an evaluation 
                        $evaluationModle->assignQuestionToEvaluation($question, $evalEntity->getId());
                    }
                    else {
                        $messages = array_merge($messages,$errors);
                    }
                }
                else {
                    array_push($messages, "One of your questions already existed.");
                }
            }
            // if there are error
            if (!empty($messages)) {
                $variables['validationError'] = $messages;
            }
             //if there's no error
            else {
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'more'), array('name' => 'coursesMore'));
                $this->redirect()->toUrl($url.'/'.$courseId);
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
        $evalId = $this->params('evalId');
        $courseId = $this->params('courseId');


        return new ViewModel($variables);
    }

    /**
     * delete course evaluation
     */
    public function deleteEvaluationAction()
    {
        $evalId = $this->params('evalId');
        $courseId = $this->params('courseId');



        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'evTemplates'), array('name' => 'EvTemplates'));
        $this->redirect()->toUrl($url);
    }

}
