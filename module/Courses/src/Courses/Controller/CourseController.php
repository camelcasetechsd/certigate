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
        if ($auth->hasIdentity() && in_array( Role::ADMIN_ROLE, $storage['roles'] )) {
            $isAdminUser = true;
        }  
        
        $data = $query->findAll(/*$entityName =*/null);
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
        
        $data = $query->findBy(/*$entityName =*/null, /*$criteria =*/array("status" => Status::STATUS_ACTIVE));
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
        
        $courseArray = array($course);
        $preparedCourseArray = $objectUtilities->prepareForDisplay($courseArray);
        $variables['course'] = reset($preparedCourseArray);
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
        if ($auth->hasIdentity() && in_array( Role::ADMIN_ROLE, $storage['roles'] )) {
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
        if ($auth->hasIdentity() && in_array( Role::ADMIN_ROLE, $storage['roles'] )) {
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
                $courseModel->save($course,/*$data =*/array() , $isAdminUser);
                
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
        $courseModel->enrollCourse($course, /*$user =*/ $currentUser);
        
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
        $courseModel->leaveCourse($course, /*$user =*/ $currentUser);
        
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'coursesCalendar'));
        $this->redirect()->toUrl($url);
    }

}

