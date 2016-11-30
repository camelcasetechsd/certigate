<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Courses\Form\CourseEventForm;
use Courses\Entity\CourseEvent;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;
use Zend\Json\Json;

/**
 * Course event Controller
 * 
 * course events entries listing
 * 
 * 
 * 
 * @package courses
 * @subpackage controller
 */
class CourseEventController extends ActionController
{

    /**
     * List course events
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\CourseEvent');
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');

        $courseId = $this->params('courseId', /* $default = */ null);
        $data = $query->filter(/* $entityName = */'Courses\Entity\CourseEvent', /* $criteria = */ $courseEventModel->getListingCriteria(/* $trainingManagerId = */ false, $courseId));
        $variables['courseEvents'] = $objectUtilities->prepareForDisplay($data);
        $variables['courseId'] = $this->params('courseId', /* $default = */ null);
        return new ViewModel($variables);
    }

    /**
     * Create new course event
     * 
     * 
     * @access public
     * @uses CourseEvent
     * @uses CourseEventForm
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Courses\Entity\CourseEvent');
        $formSmasher = $this->getServiceLocator()->get('formSmasher');
        /* @var $courseEventModel \Courses\Model\CourseEvent */
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');

        $courseId = $this->params('courseId', /* $default = */ null);
        $course = $query->findOneBy('Courses\Entity\Course', array(
            'id' => $courseId
        ));
        /**
         * Business Cases : 
         * 1- form for scpecific course where course exists and of course
         * course id is not null to prevent url manipulation
         * 
         * 2- generic form where course id is null 
         */
        if ((!is_null($course) && !is_null($courseId)) || is_null($courseId)) {

            $courseEvent = new CourseEvent();
            // setting default students number
            $courseEvent->setStudentsNo(/* $studentsNo = */ 0);
            $auth = new AuthenticationService();
            $storage = $auth->getIdentity();

            $options = array();
            $options['query'] = $query;
            $options['userId'] = $storage['id'];
            $options['courseId'] = $courseId;
            $options['applicationLocale'] = $this->getServiceLocator()->get('applicationLocale');
            $form = new CourseEventForm(/* $name = */ null, $options);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost()->toArray();
                if (!empty($courseId)) {
                    $data["course"] = $courseId;
                }
                $form->setInputFilter($courseEvent->getInputFilter());
                $form->setData($data);
                $isCustomValidationValid = $courseEventModel->validateForm($form, $data, $courseEvent, /* $isEditForm = */ false);
                if ($form->isValid() && $isCustomValidationValid === true) {
                    
                    $auth = new AuthenticationService();
                    $roles = $auth->getIdentity()['roles'];
                    $isTrainingManager = in_array(Role::TRAINING_MANAGER_ROLE, $roles);
                    
                    $courseEventModel->save($courseEvent, $data , $isTrainingManager);

                    $url = $this->getIndexUrl();
                    $this->redirect()->toUrl($url);
                }
            }

            $variables['courseEventForm'] = $this->getFormView($form);
            $variables = $formSmasher->prepareFormForDisplay($form, $variables);
            return new ViewModel($variables);
        }
        else {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'resource_not_found'));
            return $this->redirect()->toUrl($url);
        }
    }

    /**
     * Edit course event
     * 
     * 
     * @access public
     * @uses CourseEventForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params('id');
        $courseId = $this->params('courseId', /* $default = */ null);
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $formSmasher = $this->getServiceLocator()->get('formSmasher');
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');

        // checking case if courseid and eventid existed and valid  OR
        // if only event id existed and valid
        if ($courseEventModel->validateCourseEvent($courseId, $id)) {

            $courseEvent = $query->find('Courses\Entity\CourseEvent', $id);
            $auth = new AuthenticationService();
            $storage = $auth->getIdentity();

            $validationResult = $this->getServiceLocator()->get('aclValidator')->validateOrganizationAccessControl(/* $response = */$this->getResponse(), /* $role = */ Role::TRAINING_MANAGER_ROLE, /* $organization = */ $courseEvent->getAtp());
            if ($validationResult["isValid"] === false && !empty($validationResult["redirectUrl"])) {
                return $this->redirect()->toUrl($validationResult["redirectUrl"]);
            }

            $options = array();
            $options['query'] = $query;
            $options['userId'] = $storage['id'];
            $options['courseId'] = $courseId;
            $options['applicationLocale'] = $this->getServiceLocator()->get('applicationLocale');
            $form = new CourseEventForm(/* $name = */ null, $options);
            $form->bind($courseEvent);

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost()->toArray();
                if (!empty($courseId)) {
                    $data["course"] = $courseId;
                    $courseEvent->setCourse($courseId);
                }
                $form->setInputFilter($courseEvent->getInputFilter());
                $form->setData($data);

                $isCustomValidationValid = $courseEventModel->validateForm($form, $data, $courseEvent);
                if ($form->isValid() && $isCustomValidationValid === true) {

                    $auth = new AuthenticationService();
                    $roles = $auth->getIdentity()['roles'];
                    $isTrainingManager = in_array(Role::TRAINING_MANAGER_ROLE, $roles);
                    
                    $courseEventModel->save($courseEvent, [] , $isTrainingManager);

                    $url = $this->getIndexUrl();
                    $this->redirect()->toUrl($url);
                }
            }
            $variables = $formSmasher->prepareFormForDisplay($form, $variables);
            $variables['courseEventForm'] = $this->getFormView($form);
            return new ViewModel($variables);
        }
        else {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'resource_not_found'));
            return $this->redirect()->toUrl($url);
        }
    }

    /**
     * Delete course event
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $courseEvent = $query->find('Courses\Entity\CourseEvent', $id);

        $courseEvent->setStatus(Status::STATUS_INACTIVE);

        $query->save($courseEvent);

        $url = $this->getIndexUrl();
        $this->redirect()->toUrl($url);
    }

    /**
     * Add calendar event for course event
     *
     * 
     * @access public
     */
    public function addCalendarEventAction()
    {
        $url = $this->params()->fromQuery('url');
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
        $auth = new AuthenticationService();

        $data = $courseEventModel->sendCalendarAlert(/* $userData = */ $auth->getIdentity(), $url);

        return $this->getResponse()->setContent(Json::encode($data));
    }

    /**
     * Get index url
     *
     * 
     * @access private
     * @return string index url
     */
    private function getIndexUrl()
    {
        $courseId = $this->params('courseId', /* $default = */ null);
        $params = array('action' => 'index');
        if (!empty($courseId)) {
            $params["courseId"] = $courseId;
        }
        $url = $this->getEvent()->getRouter()->assemble($params, array('name' => 'courseEvents'));
        return $url;
    }

}
