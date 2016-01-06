<?php

namespace Users\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Users\Form\RoleForm;
use Users\Entity\Role;
use Users\Entity\Acl as AclEntity;

/**
 * Role Controller
 * 
 * roles and acl
 * 
 * 
 * 
 * @package users
 * @subpackage controller
 */
class RolesController extends ActionController
{

    /**
     * List roles
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get( 'wrapperQuery' )->setEntity( 'Users\Entity\Role' );
        $objectUtilities = $this->getServiceLocator()->get( 'objectUtilities' );

        $data = $query->findAll( /* $entityName = */null );
        $variables['roles'] = $objectUtilities->prepareForDisplay( $data );
        return new ViewModel( $variables );
    }

    /**
     * Create new role
     * 
     * 
     * @access public
     * @uses Role
     * @uses RoleForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get( 'wrapperQuery' )->setEntity( 'Users\Entity\Role' );
        $roleObj = new Role();

        $form = new RoleForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter( $roleObj->getInputFilter( $query ) );
            $form->setData( $data );
            if ($form->isValid()) {
                $query->save( $roleObj, $data );

                $url = $this->getEvent()->getRouter()->assemble( array('action' => 'index'), array(
                    'name' => 'roles') );
                $this->redirect()->toUrl( $url );
            }
        }

        $variables['roleForm'] = $this->getFormView( $form );
        return new ViewModel( $variables );
    }

    /**
     * Edit role
     * 
     * 
     * @access public
     * @uses RoleForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params( 'id' );
        $query = $this->getServiceLocator()->get( 'wrapperQuery' );
        $roleObj = $query->find( 'Users\Entity\Role', $id );

        $form = new RoleForm();
        $form->bind( $roleObj );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter( $roleObj->getInputFilter( $query ) );
            $form->setData( $data );
            if ($form->isValid()) {
                $query->save( $roleObj );

                $url = $this->getEvent()->getRouter()->assemble( array('action' => 'index'), array(
                    'name' => 'roles') );
                $this->redirect()->toUrl( $url );
            }
        }

        $variables['roleForm'] = $this->getFormView( $form );
        return new ViewModel( $variables );
    }

    /**
     * Delete role
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params( 'id' );
        $query = $this->getServiceLocator()->get( 'wrapperQuery' );
        $roleObj = $query->find( 'Users\Entity\Role', $id );

        $query->remove( $roleObj );

        $url = $this->getEvent()->getRouter()->assemble( array('action' => 'index'), array(
            'name' => 'roles') );
        $this->redirect()->toUrl( $url );
    }

    /**
     * Define role privileges ( ACL role <-> module <-> route )
     * 
     * @access public
     * @return ViewModel 
     */
    public function privilegesAction()
    {
        $id = $this->params( 'id' );
        /* @var $query \Utilities\Service\Query\Query */
        $query = $this->getServiceLocator()->get( 'wrapperQuery' );
        $em = $query->entityManager;

        $roleObj = $query->find( 'Users\Entity\Role', $id );
        $rolePrivileges = $query->findAll( 'Users\Entity\Acl', array('role' => $roleObj) );

        $request = $this->getRequest();
        if ($request->isPost()) {

            // delete old privileges
            foreach ($rolePrivileges as $p) {
                $query->remove( $p );
            }

            // insert new privileges
            $data = $request->getPost()->toArray();

            foreach ($data['privileges'] as $p) {
                list($module, $route) = explode( "-", $p );
                $aclEntity = new AclEntity();
                $aclEntity->setModule( $module );
                $aclEntity->setRoute( $route );
                $aclEntity->setRole( $roleObj );

                $em->persist( $aclEntity );
            }
            $em->flush();

            $url = $this->getEvent()->getRouter()->assemble( array('action' => 'index'), array(
                'name' => 'roles') );
            $this->redirect()->toUrl( $url );
        }

        $manager = $this->getServiceLocator()->get( 'ModuleManager' );
        $loadedModules = $manager->getLoadedModules();
        $excludedModules = $loadedModules['Users']->getConfig()['roles_management']['excluded_modules'];
        $filtereModules = [];
        foreach ($loadedModules as $k => $v) {
            if (!in_array( $k, $excludedModules )) {
                $filtereModules[$k] = $v;
            }
        }
        
        $roleRoutes = [];
        
        foreach($rolePrivileges as $p){
           $roleRoutes[] = implode("-", [$p->getModule(),$p->getRoute()]);
        }
        
        foreach ($filtereModules as $module => $object) {
            $routes = array_keys( $object->getConfig()['router']['routes'] );
            $newRoutes = [];
            foreach ($routes as $r) {

                if (in_array( implode( "-", [$module,$r]), $roleRoutes )) {
                    $newRoutes[] = [
                        'name' => $r,
                        'checked' => true
                    ];
                } else {
                    $newRoutes[] = [
                        'name' => $r,
                        'checked' => false
                    ];
                }
            }
            
            $modulesRoutes[] = [
                'module' => $module,
                'routes' => $newRoutes
            ];
        }

        return new ViewModel( ['modulesRoutes' => $modulesRoutes, 'role' => $roleObj,] );
    }

}
