<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\PageForm;
use CMS\Entity\Page;
use Utilities\Service\Status;
use CMS\Form\FormViewHelper;
use Zend\Authentication\AuthenticationService;
use Users\Entity\Role;

/**
 * Page Controller
 * 
 * pages entries listing
 * 
 * 
 * 
 * @package cms
 * @subpackage controller
 */
class PageController extends ActionController
{

    /**
     * List pages
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $auth = new AuthenticationService();
        $storage = $auth->getIdentity();
        $isAdminUser = false;
        if ($auth->hasIdentity() && in_array( Role::ADMIN_ROLE, $storage['roles'] )) {
            $isAdminUser = true;
        }
        $query = $this->getServiceLocator()->get( 'wrapperQuery' )->setEntity( 'CMS\Entity\Page' );
        $objectUtilities = $this->getServiceLocator()->get( 'objectUtilities' );

        $data = $query->findAll( /* $entityName = */'CMS\Entity\Page' );
        $variables['pages'] = $objectUtilities->prepareForDisplay( $data );
        $variables['isAdminUser'] = $isAdminUser;
        return new ViewModel( $variables );
    }

    /**
     * Create new page
     * 
     * 
     * @access public
     * @uses Page
     * @uses PageForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get( 'wrapperQuery' )->setEntity( 'CMS\Entity\Page' );
        $pageObj = new Page();

        $options = array();
        $options['query'] = $query;
        $form = new PageForm( /* $name = */ null, $options );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter( $pageObj->getInputFilter( $query ) );
            $form->setData( $data );
            if ($form->isValid()) {
                $query->save( $pageObj, $data );

                $url = $this->getEvent()->getRouter()->assemble( array('action' => 'index'), array(
                    'name' => 'cmsPage') );
                $this->redirect()->toUrl( $url );
            }
        }

        $formViewHelper = new FormViewHelper();
        $this->setFormViewHelper( $formViewHelper );
        $variables['pageForm'] = $this->getFormView( $form );
        return new ViewModel( $variables );
    }

    /**
     * Edit page
     * 
     * 
     * @access public
     * @uses PageForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params( 'id' );
        $query = $this->getServiceLocator()->get( 'wrapperQuery' );
        $pageObj = $query->find( 'CMS\Entity\Page', $id );
        $request = $this->getRequest();
        if (!$request->isPost()) {
            // extract body data to be able to display it in it's natural form
            $pageObj->body = $pageObj->getBody();
        }

        $options = array();
        $options['query'] = $query;
        $form = new PageForm( /* $name = */ null, $options );
        $form->bind( $pageObj );

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter( $pageObj->getInputFilter( $query ) );
            $form->setData( $data );
            if ($form->isValid()) {
                $query->save( $pageObj );

                $url = $this->getEvent()->getRouter()->assemble( array('action' => 'index'), array(
                    'name' => 'cmsPage') );
                $this->redirect()->toUrl( $url );
            }
        }

        $formViewHelper = new FormViewHelper();
        $this->setFormViewHelper( $formViewHelper );
        $variables['pageForm'] = $this->getFormView( $form );
        return new ViewModel( $variables );
    }

    /**
     * Delete page
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params( 'id' );
        $query = $this->getServiceLocator()->get( 'wrapperQuery' );
        $pageObj = $query->find( 'CMS\Entity\Page', $id );

        $pageObj->setStatus( Status::STATUS_INACTIVE );

        $query->setEntity( 'CMS\Entity\Page' )->save( $pageObj );

        $url = $this->getEvent()->getRouter()->assemble( array('action' => 'index'), array(
            'name' => 'cmsPage') );
        $this->redirect()->toUrl( $url );
    }
    
    /**
     * Activate page
     *
     * 
     * @access public
     */
    public function activateAction()
    {
        $id = $this->params( 'id' );
        $query = $this->getServiceLocator()->get( 'wrapperQuery' );
        $pageObj = $query->find( 'CMS\Entity\Page', $id );

        $pageObj->setStatus( Status::STATUS_ACTIVE );

        $query->setEntity( 'CMS\Entity\Page' )->save( $pageObj );

        $url = $this->getEvent()->getRouter()->assemble( array('action' => 'index'), array(
            'name' => 'cmsPage') );
        $this->redirect()->toUrl( $url );
    }

    /**
     * List page history
     *
     * 
     * @access public
     */
    public function historyAction()
    {
        $id = $this->params( 'id' );
        $query = $this->getServiceLocator()->get( 'wrapperQuery' );
        $versionModel = $this->getServiceLocator()->get( 'Versioning\Model\Version' );
        $pageModel = $this->getServiceLocator()->get( 'CMS\Model\Page' );
        $page = $query->find( 'CMS\Entity\Page', $id );
        $logs = $versionModel->prepareLogs( $page );
        
        $variables = array(
            "logs" => $pageModel->prepareHistory( $logs ),
            "page" => $page
        );
        return new ViewModel( $variables );
    }

    /**
     * View page
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function viewAction()
    {
        $staticPagePath = $this->getRequest()->getRequestUri();
        $query = $this->getServiceLocator()->get( 'wrapperQuery' );

        $page = $query->setEntity( 'CMS\Entity\Page' )->entityRepository->getPageByPath( $staticPagePath );
        $variables = array(
            "title" => $page->getTitle(),
            "body" => $page->getBody()
        );
        return new ViewModel( $variables );
    }

}
