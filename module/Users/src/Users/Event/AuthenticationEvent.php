<?php

namespace Users\Event;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Request as HttpRequest ;
use Zend\Http\Response;

/**
 * Authentication Event Handler Class
 *
 * This Event Handles Authentication
 * 
 * 
 * @author Marco Neumann <webcoder@binware.org>
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license http://binware.org/license/index/type:new-bsd New BSD License
 * 
 * 
 * @package users
 * @subpackage event
 */
class AuthenticationEvent extends AbstractActionController {

    /**
     * preDispatch Event Handler
     * Handle authentication process
     * Decide where user should be redirected to when logged in or not
     * 
     * 
     * @access public
     * @uses AuthenticationService
     * @uses Response
     * 
     * @param \Zend\Mvc\MvcEvent $event
     * @throws \Exception
     */
    public function preDispatch(MvcEvent $event) {
        
        // ACL dispatcher is used only in HTTP requests not console requests
        if(! $event->getRequest() instanceof HttpRequest){
            return;
        }
        $userAuth = new AuthenticationService();
        $user = array();
        $signInController = 'DefaultModule\Controller\Sign';
        if ($userAuth->hasIdentity()) {
            $user = $userAuth->getIdentity();
        }


        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        if ($userAuth->hasIdentity() && isset($user['status']) && $user['status'] == 2) {
            $userAuth->clearIdentity();
            // redirect to sign/out
            $url = $event->getRouter()->assemble(array('action' => 'out'), array('name' => 'defaultSign'));
        } else if ($userAuth->hasIdentity() && $controller == $signInController &&
                $action == 'in') {
            // redirect to index
            $url = $event->getRouter()->assemble(array('action' => 'index'), array('name' => 'home'));
        } 

        if (isset($url)) {
            $event->setResponse(new Response());
            $this->redirect()->getController()->setEvent($event);
            $response = $this->redirect()->toUrl($url);
            return $response;
        }
    }

}
