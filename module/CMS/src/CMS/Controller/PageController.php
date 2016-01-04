<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\PageForm;
use CMS\Entity\Page;

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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\Page');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        
        $data = $query->findAll(/*$entityName =*/null);
        $variables['pages'] = $objectUtilities->prepareForDisplay($data);
        return new ViewModel($variables);
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
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\Page');
        $pageObj = new Page();

        $options = array();
        $options['query'] = $query;
        $form = new PageForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($pageObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($pageObj, $data);
                
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsPage'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['pageForm'] = $this->getFormView($form);
        return new ViewModel($variables);
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
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $pageObj = $query->find('CMS\Entity\Page', $id);

        $options = array();
        $options['query'] = $query;
        $form = new PageForm(/* $name = */ null, $options);
        $form->bind($pageObj);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($pageObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($pageObj);
                
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsPage'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['pageForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }


}

