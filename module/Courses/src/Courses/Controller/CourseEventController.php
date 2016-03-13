<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Courses\Form\CourseEventForm;
use Courses\Entity\CourseEvent;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Utilities\Service\Status;

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

        $data = $query->filter(/* $entityName = */'Courses\Entity\CourseEvent', /*$criteria =*/$courseEventModel->getListingCriteria());
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
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
        $courseEvent = new CourseEvent();
        // setting default students number
        $courseEvent->setStudentsNo(/* $studentsNo = */ 0);
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();

        $options = array();
        $options['query'] = $query;
        $options['userId'] = $storage['id'];
        $options['courseId'] = $courseId = $this->params('courseId', /* $default = */ null);
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
                $query->setEntity('Courses\Entity\CourseEvent')->save($courseEvent->setStatus(Status::STATUS_ACTIVE), $data);

                $url = $this->getIndexUrl();
                $this->redirect()->toUrl($url);
            }
        }

        $variables['courseEventForm'] = $this->getFormView($form);
        return new ViewModel($variables);
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
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $courseEventModel = $this->getServiceLocator()->get('Courses\Model\CourseEvent');
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
        $options['courseId'] = $courseId = $this->params('courseId', /* $default = */ null);
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
                $query->setEntity('Courses\Entity\CourseEvent')->save($courseEvent->setStatus(Status::STATUS_ACTIVE));
                $url = $this->getIndexUrl();
                $this->redirect()->toUrl($url);
            }
        }
        $variables['courseEventForm'] = $this->getFormView($form);
        return new ViewModel($variables);
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
        if(! empty($courseId)){
            $params["courseId"] = $courseId;
        }
        $url = $this->getEvent()->getRouter()->assemble($params, array('name' => 'courseEvents'));
        return $url;
    }
}
