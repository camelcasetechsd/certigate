<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Courses\Form\CourseForm;
use Courses\Entity\Course;

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
        
        $data = $query->findAll(/*$entityName =*/null);
        $variables['courses'] = $objectUtilities->prepareForDisplay($data);
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
        $courseObj = new Course();

        $options = array();
        $options['query'] = $query;
        $form = new CourseForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($courseObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($courseObj, $data);
                
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'course'));
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
        $courseObj = $query->find('Courses\Entity\Course', $id);
        // extract body data to be able to display it in it's natural form
        $courseObj->body = $courseObj->getBody();
        
        $options = array();
        $options['query'] = $query;
        $form = new CourseForm(/* $name = */ null, $options);
        $form->bind($courseObj);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($courseObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($courseObj);
                
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'course'));
                $this->redirect()->toUrl($url);
            }
        }

        $formViewHelper = new FormViewHelper();
        $this->setFormViewHelper($formViewHelper);
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
        $courseObj = $query->find('Courses\Entity\Course', $id);
        
        $menuItem = $courseObj->getMenuItem();
        $menuItem->setStatus(Status::STATUS_INACTIVE);

        $query->setEntity('Courses\Entity\MenuItem')->save($menuItem);
        
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'course'));
        $this->redirect()->toUrl($url);
    }
    
    /**
     * View course
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function viewAction()
    {
        $staticCoursePath = $this->getRequest()->getRequestUri();
        $query = $this->getServiceLocator()->get('wrapperQuery');
        
        $course = $query->setEntity('Courses\Entity\Course')->entityRepository->getCourseByPath($staticCoursePath);
        $variables = array(
            "title" => $course->getTitle(),
            "body" => $course->getBody()
        );
        return new ViewModel($variables);
    }

}

