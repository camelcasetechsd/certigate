<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\MenuForm;
use CMS\Entity\Menu;
use Utilities\Service\Status;

/**
 * Menu Controller
 * 
 * menus entries listing
 * 
 * 
 * 
 * @package cms
 * @subpackage controller
 */
class MenuController extends ActionController
{

    /**
     * List menus
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\Menu');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        
        $data = $query->findAll(/*$entityName =*/null);
        $variables['menus'] = $objectUtilities->prepareForDisplay($data);
        return new ViewModel($variables);
    }

    /**
     * Create new menu
     * 
     * 
     * @access public
     * @uses Menu
     * @uses MenuForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\Menu');
        $menuObj = new Menu();

        $form = new MenuForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($menuObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($menuObj, $data);
                
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenu'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['menuForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Edit menu
     * 
     * 
     * @access public
     * @uses MenuForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $menuObj = $query->find('CMS\Entity\Menu', $id);

        $form = new MenuForm();
        $form->bind($menuObj);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($menuObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($menuObj);
                
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenu'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['menuForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }
    
    /**
     * Delete menu
     *
     * 
     * @access public
     */
    public function deactivateAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $menuObj = $query->find('CMS\Entity\Menu', $id);
        
        $menuObj->setStatus(Status::STATUS_INACTIVE);

        $query->save($menuObj);
        
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenu'));
        $this->redirect()->toUrl($url);
    }
    
    /**
     * activate menu
     *
     * 
     * @access public
     */
    public function activateAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $menuObj = $query->find('CMS\Entity\Menu', $id);
        
        $menuObj->setStatus(Status::STATUS_ACTIVE);

        $query->save($menuObj);
        
        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenu'));
        $this->redirect()->toUrl($url);
    }


}

