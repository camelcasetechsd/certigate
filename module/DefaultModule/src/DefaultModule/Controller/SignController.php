<?php

namespace DefaultModule\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use DefaultModule\Form\SigninForm;
use Zend\Authentication\AuthenticationService;

/**
 * Sign Controller
 * 
 * Handles Authentication processes
 * 
 * 
 * 
 * @package defaultModule
 * @subpackage controller
 */
class SignController extends ActionController
{

    /**
     * Default action
     * 
     * 
     * @access public
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * User Login
     * 
     * 
     * @uses SigninForm
     * 
     * @access public
     * @return ViewModel
     */
    public function inAction()
    {
        $variables = array();
        $form = new SigninForm();
        $request = $this->getRequest();
        //checking if we got a new post request
        if ($request->isPost()) {
            $form->setData($request->getPost());
            // checking if the form is valid
            if ($form->isValid()) {
                $auth = $this->getServiceLocator()->get('Users\Auth\Authentication')->setRequest($request);
                // validate authentication data
                // set user-related data in session
                $result = $auth->authenticateMe();
                if ($result->isValid()) {
                    $auth->newSession();
                    $cmsCacheHandler = $this->getServiceLocator()->get('cmsCacheHandler');
                    $systemCacheHandler = $this->getServiceLocator()->get('systemCacheHandler');
                    $forceFlush = (APPLICATION_ENV == "production" )? false : true;
                    $cmsCacheHandler->prepareCachedCMSData($forceFlush);
                    $systemCacheHandler->prepareCachedSystemData($forceFlush);
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'home'));
                    $this->redirect()->toUrl($url);
                } else {
                    $errorMessages = array();
                    $errorMessages[]['message'] = "Username and password are invalid !";
                    $variables['message'] = $errorMessages;
                }
            }
        }
        $variables['form'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * User Logout
     * 
     * 
     * @uses AuthenticationService
     * 
     * @access public
     */
    public function outAction()
    {
        $auth = $this->getServiceLocator()->get('Users\Auth\Authentication');
        $auth->clearSession();
        
        // Redirect to login page again 
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'in'), array('name' => 'defaultSign'));
        $this->redirect()->toUrl($url);
    }


}

