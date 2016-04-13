<?php

namespace CertigateAcl\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as ZendRole;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;
use Zend\Console\Request;

class CertigateAclPlugin extends AbstractPlugin
{

    public function doAuthorization(\Zend\Mvc\MvcEvent $event)
    {
        // Ignore ACL if in console
        if ($event->getRequest() instanceof Request) {
            return;
        }
        $controller = $event->getTarget();
        $controllerClass = get_class($controller);
        $moduleName = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $routeMatch = $event->getRouteMatch()->getMatchedRouteName();

        $manager = $this->getController()->getServiceLocator()->get('ModuleManager');
        $loadedModules = $manager->getLoadedModules();
        $certigateAclConfig = $loadedModules['CertigateAcl']->getConfig()['roles_management'];
        $excludedModules = $certigateAclConfig['excluded_modules'];
        $anonymousRoutes = $certigateAclConfig['anonymous_routes'];

        $signInController = 'DefaultModule\Controller\SignController';
        $router = $event->getRouter();

        // return if the target is in excluded modules 
        if (in_array($moduleName, $excludedModules)) {
            return;
        }

        // return if not logged in
        $auth = new AuthenticationService();
        $authenticated = true;
        if (!$auth->hasIdentity()) {
            $authenticated = false;
        }

        // set ACL
        $acl = new ZendAcl();
        $acl->deny();

        // Defining Resources
        foreach ($loadedModules as $k => $v) {
            if (!in_array($k, $excludedModules)) {
                $acl->addResource($k);
            }
        }

        if (count($anonymousRoutes) > 0) {
            $acl->addRole(new ZendRole(Role::ANONYMOUS_ROLE));
            foreach ($anonymousRoutes as $privilege => $anonymousRoute) {
                $acl->allow(/* $roles = */ Role::ANONYMOUS_ROLE, $anonymousRoute['resource'], $privilege);
            }
        }

        if ($authenticated === true) {
            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getController()->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            // Roles 
            $roles = $em->getRepository('Users\Entity\Role')->findAll();

            foreach ($roles as $r) {
                $acl->addRole(new ZendRole($r->getName()));
            }

            // Fetching Privileges
            $privileges = $em->getRepository('Users\Entity\Acl')->findAll();
            $allRolesPriveleges = [];

            foreach ($privileges as $p) {
                $allRolesPriveleges[$p->getRole()->getName()][$p->getModule()][] = $p->getRoute();
            }

            // Defining Permissions
            $acl->allow(Role::ADMIN_ROLE); // allow everything for Admin 
            foreach ($allRolesPriveleges as $role => $modules) {
                foreach ($modules as $module => $routes) {
                    foreach ($routes as $r) {
                        $acl->allow($role, $module, $r);
                    }
                }
            }
            // get logged in user roles
            $userRoles = $em->find('\Users\Entity\User', $auth->getIdentity()['id'])->getRoles();
            $isAllowed = false;
            if ($acl->isAllowed(Role::ANONYMOUS_ROLE, $moduleName, $routeMatch)) {
                $isAllowed = true;
            }
            else {
                foreach ($userRoles as $userRole) {
                    if ($acl->isAllowed($userRole->getName(), $moduleName, $routeMatch)) {
                        $isAllowed = true;
                        break;
                    }
                }
            }
            if ($isAllowed === false) {
                $url = $router->assemble(array(), array('name' => 'noaccess'));
                $status = 302;
            }
        }

        if ($authenticated === false && $controllerClass != $signInController) {
            if (!$acl->isAllowed(Role::ANONYMOUS_ROLE, $moduleName, $routeMatch)) {
                // redirect to sign/in
                $redirectBackUrl = $event->getRequest()->getRequestUri();
                $url = $router->assemble(
                        array(
                    'action' => 'in'
                        ), array(
                    'name' => 'defaultSign',
                    'query' => array(
                        'redirectBackUrl' => $redirectBackUrl
                            )
                        )
                );
                $status = 200;
            }
        }

        if (isset($url) && isset($status)) {
            $response = $event->getResponse();
            $response->setStatusCode(302);

            // redirect to login page or other page.
            $response->getHeaders()->addHeaderLine('Location', $url);
            $event->stopPropagation();
        }
    }

}
