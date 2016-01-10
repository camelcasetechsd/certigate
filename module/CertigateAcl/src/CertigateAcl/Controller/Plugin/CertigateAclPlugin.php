<?php

namespace CertigateAcl\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as ZendRole;
use Zend\Authentication\AuthenticationService;

class CertigateAclPlugin extends AbstractPlugin
{

    public function doAuthorization( \Zend\Mvc\MvcEvent $e )
    {
        $controller = $e->getTarget();
        $controllerClass = get_class( $controller );
        $moduleName = substr( $controllerClass, 0, strpos( $controllerClass, '\\' ) ) ;
        $routeMatch = $e->getRouteMatch()->getMatchedRouteName();
        
        $manager = $this->getController()->getServiceLocator()->get( 'ModuleManager' );
        $loadedModules = $manager->getLoadedModules();
        $excludedModules = $loadedModules['CertigateAcl']->getConfig()['roles_management']['excluded_modules'];
        
        // return if the target is in excluded modules 
        if(in_array( $moduleName, $excludedModules)){
            return ;
        }
        
        // return if not logged in
        $auth = new AuthenticationService();
        if (!$auth->hasIdentity()) {
            return;
        }

        // set ACL
        $acl = new ZendAcl();
        $acl->deny();
        
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getController()->getServiceLocator()->get( 'Doctrine\ORM\EntityManager' );

        // Roles 
        $roles = $em->getRepository( 'Users\Entity\Role' )->findAll();

        foreach ($roles as $r) {
            $acl->addRole( new ZendRole( $r->getName() ) );
        }
        
        // Fetching Privileges
        $privileges = $em->getRepository( 'Users\Entity\Acl' )->findAll();
        $allRolesPriveleges = [];

        foreach ($privileges as $p) {
            $allRolesPriveleges[$p->getRole()->getName()][$p->getModule()][] = $p->getRoute();
        }

        // Defining Resources
        foreach ($loadedModules as $k => $v) {
            if (!in_array( $k, $excludedModules )) {
                $acl->addResource($k);
            }
        }
        
        // Defining Permissions
        $acl->allow('Admin'); // allow everything for Admin 
        foreach ($allRolesPriveleges as $role => $modules) {
            foreach ($modules as $module => $routes) {
                foreach ($routes as $r) {
                    $acl->allow( $role, $module, $r );
                }
            }
        }
        
        // get logged in user roles
        $userRoles = $em->find( '\Users\Entity\User', $auth->getIdentity()['id'])->getRoles();

        foreach ($userRoles as $role) {
            if (!$acl->isAllowed( $role->getName(), $moduleName, $routeMatch )) {
                $router = $e->getRouter();
                $url = $router->assemble( array(), array('name' => 'noaccess') );
                
                $response = $e->getResponse();
                $response->setStatusCode( 302 );
                
                // redirect to login page or other page.
                $response->getHeaders()->addHeaderLine( 'Location', $url );
                $e->stopPropagation();
            }
        }
    }

}
