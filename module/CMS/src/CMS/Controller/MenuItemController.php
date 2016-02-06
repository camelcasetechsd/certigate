<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\MenuItemForm;
use CMS\Entity\MenuItem;
use Utilities\Service\Status;
use CMS\Form\FormViewHelper;

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
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $menuItemModel = $this->getServiceLocator()->get('CMS\Model\MenuItem');

        $pageNumber = $this->getRequest()->getQuery('page');
        $menuItemModel->setPage($pageNumber);

        // know the number of pages
        $numberOfPages = $menuItemModel->getNumberOfPages();
        //create an array of page numbers
        if ($numberOfPages > 1) {
            $pageNumbers = range(1, $numberOfPages);
        }
        else {
            $pageNumbers = array();
        }
        $variables['menuItems'] = $objectUtilities->prepareForDisplay($menuItemModel->getCurrentItems());
        $variables['pageNumbers'] = $pageNumbers;
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
            $form->setInputFilter($menuItemObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($menuItemObj, $data);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
                $this->redirect()->toUrl($url);
            }
        }

        $formViewHelper = new FormViewHelper();
        $this->setFormViewHelper($formViewHelper);
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
        // hide current menu from possible parents options
        $options['hiddenMenuItemsIds'] = array($id);
        $form = new MenuItemForm(/* $name = */ null, $options);
        $menu = $menuItemObj->getMenu();
        // menu hidden field can hold only id, not an object
        $menuItemObj->setMenu($menu->getId());
        $form->bind($menuItemObj);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($menuItemObj->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->setEntity('CMS\Entity\MenuItem')->save($menuItemObj, $data);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
                $this->redirect()->toUrl($url);
            }
        }

        $menuItemObj->setMenu($menu);
        $formViewHelper = new FormViewHelper();
        $this->setFormViewHelper($formViewHelper);
        $variables['menuItemForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    /**
     * Delete menu item
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $menuItemObj = $query->find('CMS\Entity\MenuItem', $id);

        $menuItemObj->setStatus(Status::STATUS_INACTIVE);

        $query->save($menuItemObj);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
        $this->redirect()->toUrl($url);
    }

}
