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

        $courseArray = array($course);
        $preparedCourseArray = $courseModel->setCanEnroll($objectUtilities->prepareForDisplay($courseArray));
        $preparedCourse = reset($preparedCourseArray);
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
                $courseModel->save($course, /* $data = */ array(), $isAdminUser);

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

    /**
     * Download resource
     *
     * 
     * @access public
     */
    public function downloadAction()
    {
        $id = $this->params('id');
        $resource = $this->params('resource');
        $name = $this->params('name');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $course = $query->find('Courses\Entity\Course', $id);
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $evaluationModel = $this->getServiceLocator()->get('Courses\Model\Evaluation');


        $courseArray = array($course);
        $preparedCourseArray = $courseModel->setCanEnroll($courseArray);
        $preparedCourse = reset($preparedCourseArray);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        if ($auth->hasIdentity() && in_array(Role::STUDENT_ROLE, $storage['roles']) && $preparedCourse->canLeave === false) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'noaccess'));
            $this->redirect()->toUrl($url);
        }
        else {

            $file = $courseModel->getResource($course, $resource, $name);
            $response = new Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(200);
            $response->setStreamName(basename($file));
            $headers = new Headers();
            $headers->addHeaders(array(
                'Content-Disposition' => 'attachment; filename="' . basename($file) . '"',
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => filesize($file),
                'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
                'Cache-Control' => 'must-revalidate',
                'Pragma' => 'public'
            ));
            $response->setHeaders($headers);
            return $response;
        }
    }

    /**
     * This function meant to redirect Admin to create if there's no evaluation template 
     * or to edit if there's already created evaluation template
     */
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
            $messages = array();
            //string and length validators
            $stringValidator = new \Zend\I18n\Validator\Alnum(array('allowWhiteSpace' => true));
            $lengthValidator = new \Zend\Validator\StringLength(array('min' => 15));

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
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $eval = $query->find('Courses\Entity\Evaluation', $id);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAdminUser = true;
        }

        $options = array();
        $options['query'] = $query;
        $options['isAdminUser'] = $isAdminUser;
        $form = new \Courses\Form\EvaluationTemplateForm(/* $name = */ null, $options);
        $form->bind($eval);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            if ($isAdminUser) {
                $data['isAdmin'] = 1;
            }
            $form->setInputFilter($eval->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $courseModel->saveEvaluation($eval, /* $data = */ array());

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['evaluationForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    public function deleteEvTemplateAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $eval = $query->find('Courses\Entity\Evaluation', $id);

        $query->remove($eval);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'evTemplates'), array('name' => 'EvTemplates'));
        $this->redirect()->toUrl($url);
    }

    /**
     * List Course Evaluations (admin's default evaluation templates & atp's course evaluations)
     * 
     * 
     * @return ViewModel
     */
    public function evaluationsAction()
    {
        $courseId = $this->params('courseId');
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;

        if ($auth->hasIdentity() && in_array(array(Role::ADMIN_ROLE, Role::TRAINING_MANAGER_ROLE), $storage['roles'])) {
            $isAdminUser = true;
        }
//
        $course = $query->findOneBy('Courses\Entity\Course', array('id' => $courseId));
        $evals = $course->getEvaluations();

        $variables['courseId'] = $courseId;
        $variables['questions'] = $evals;

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
        $courseId = $this->params('courseId');

        $query = $this->getServiceLocator()->get('wrapperQuery');
        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
        $evaluation = new \Courses\Entity\Evaluation();
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        //intialize authority check
        $isAutherizedUser = false;
        $isAdminUser = false;
        $isAtpUser = false;
        //admin only 
        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
            $isAutherizedUser = true;
            $isAdminUser = true;
        }
        //atp only
        else if ($auth->hasIdentity() && in_array(Role::TRAINING_MANAGER_ROLE, $storage['roles'])) {
            $isAutherizedUser = true;
            $isAtpUser = true;
        }


        $options = array();
        $options['query'] = $query;
        //admin user means admin  or atp already checked before
        $options['isAdminUser'] = $isAutherizedUser;
        $form = new \Courses\Form\EvaluationTemplateForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();

            if ($isAdminUser) {
                //if not admin so its an atp
                $data["isAdmin"] = 1;
            }
            else if ($isAtpUser) {
                $data["isAdmin"] = 0;
            }
            $form->setInputFilter($evaluation->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $courseModel->saveEvaluation($evaluation, $data);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'evaluations'), array('name' => 'courseEvaluations'));
                $this->redirect()->toUrl($url . '/' . $courseId);
            }
        }

        $variables['evaluationForm'] = $this->getFormView($form);


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
//        $query = $this->getServiceLocator()->get('wrapperQuery');
//        $courseModel = $this->getServiceLocator()->get('Courses\Model\Course');
//        $eval = $query->find('Courses\Entity\Evaluation', $id);
//        $auth = new AuthenticationService();
//        $storage = $auth->getIdentity();
//        $isAdminUser = false;
//        if ($auth->hasIdentity() && in_array(Role::ADMIN_ROLE, $storage['roles'])) {
//            $isAdminUser = true;
//        }
//
//        $options = array();
//        $options['query'] = $query;
//        $options['isAdminUser'] = $isAdminUser;
//        $form = new \Courses\Form\EvaluationTemplateForm(/* $name = */ null, $options);
//        $form->bind($eval);
//
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $data = $request->getPost()->toArray();
//            if ($isAdminUser) {
//                $data['isAdmin'] = 1;
//            }
//            $form->setInputFilter($eval->getInputFilter());
//            $form->setData($data);
//            if ($form->isValid()) {
//                $courseModel->saveEvaluation($eval, /* $data = */ array(), $isAdminUser);
//
//                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'courses'));
//                $this->redirect()->toUrl($url);
//            }
//        }
//
//        $variables['evaluationForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * delete course evaluation
     */
    public function deleteEvaluationAction()
    {
        $evalId = $this->params('evalId');
        $courseId = $this->params('courseId');
//        $id = $this->params('id');
//        $query = $this->getServiceLocator()->get('wrapperQuery');
//        $eval = $query->find('Courses\Entity\Evaluation', $id);
//
//        $query->remove($eval);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'evTemplates'), array('name' => 'EvTemplates'));
        $this->redirect()->toUrl($url);
    }

}
