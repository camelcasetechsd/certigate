<?php

namespace Courses\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Courses\Form\ResourceForm;
use Courses\Entity\Resource;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Zend\Form\FormInterface;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

/**
 * Resource Controller
 * 
 * resources entries listing
 * 
 * 
 * 
 * @package courses
 * @subpackage controller
 */
class OutlineController extends ActionController
{

    /**
     * List outlines
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $courseId = $this->params('courseId');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $course = $query->find(/* $entityName = */'Courses\Entity\Course', $courseId);
        // if course does not exist
        if ($course == null) {
            $this->getResponse()->setStatusCode(302);
            $url = $this->getEvent()->getRouter()->assemble(array(), array('name' => 'resource_not_found'));
            $this->redirect()->toUrl($url);
        }
        else {
            $data = $course->getOutlines();
            $variables['outlines'] = $data;
            return new ViewModel($variables);
        }
    }

}
