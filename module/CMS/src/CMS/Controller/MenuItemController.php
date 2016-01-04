<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\MenuItemForm;
use CMS\Entity\MenuItem;

/**
 * MenuItem Controller
 * 
 * menuItems entries listing
 * 
 * 
 * 
 * @package cms
 * @subpackage controller
 */
class MenuItemController extends ActionController
{

    /**
     * List menuItems
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\MenuItem');
        
        $data = $query->findAll(/*$entityName =*/null);
        $variables['menuItems'] = $data;
        return new ViewModel($variables);
    }

    /**
     * Create new menuItem
     * 
     * 
     * @access public
     * @uses MenuItem
     * @uses MenuItemForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('CMS\Entity\MenuItem');
        $menuItemObj = new MenuItem();

        $options = array();
        $options['query'] = $query;
        $form = new MenuItemForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($menuItemObj->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($menuItemObj, $data);
                
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['menuItemForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Edit menuItem
     * 
     * 
     * @access public
     * @uses MenuItemForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $menuItemObj = $query->find('CMS\Entity\MenuItem', $id);

        $options = array();
        $options['query'] = $query;
        $form = new MenuItemForm(/* $name = */ null, $options);
        $form->bind($menuItemObj);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($menuItemObj->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($menuItemObj);
                
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables['menuItemForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }


}

