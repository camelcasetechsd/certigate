<?php

namespace CMS\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use CMS\Form\MenuItemForm;
use CMS\Form\MenuItemFilterForm;
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
     * @uses MenuItemFilterForm
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        $menuItemModel = $this->getServiceLocator()->get('CMS\Model\MenuItem');
        $query = $this->getServiceLocator()->get('wrapperQuery');

        $request = $this->getRequest();
        $data = $request->getQuery()->toArray();
        $menuItemModel->filterMenuItems($data);

        $pageNumber = $this->getRequest()->getQuery('page');
        $menuItemModel->setPage($pageNumber);

        $pageNumbers = $menuItemModel->getPagesRange($pageNumber);
        $nextPageNumber = $menuItemModel->getNextPageNumber($pageNumber);
        $previousPageNumber = $menuItemModel->getPreviousPageNumber($pageNumber);
        $variables['menuItems'] = $objectUtilities->prepareForDisplay($menuItemModel->getCurrentItems());
        $variables['pageNumbers'] = $pageNumbers;
        $variables['hasPages'] = ( count($pageNumbers) > 0 ) ? true : false;
        $variables['nextPageNumber'] = $nextPageNumber;
        $variables['previousPageNumber'] = $previousPageNumber;

        $options = array();
        $options['query'] = $query;
        $form = new MenuItemFilterForm(/* $name = */ null, $options);
        $form->setData($data);
        $variables['filterForm'] = $this->getFormView($form);
        $variables['filterQuery'] = $this->getFilterQuery();
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
        $formSmasher = $this->getServiceLocator()->get('formSmasher');

        $menuItemModel = $this->getServiceLocator()->get('CMS\Model\MenuItem');
        $menuItemObj = new MenuItem();

        $options = array();
        $options['query'] = $query;
        $form = new MenuItemForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($menuItemObj->getInputFilter($query));
            $form->setData($data);
            $menuItemModel->setFormRequiredFields($form, $data);
            if ($form->isValid()) {
                /**
                *  query save function assumes that the name of the field equeal to the name of
                *  the column in database so we adding data with the same name to override this
                *  problem
                */
                $data['parent'] = empty($data['optgroup-parent'])?null:$data['optgroup-parent'];
                $query->save($menuItemObj, $data);
                $this->flushMenuCache();

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables = $formSmasher->prepareFormForDisplay($form, /* elements containers */ $variables, array('buttons'));
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
        $formSmasher = $this->getServiceLocator()->get('formSmasher');

        $menuItemObj = $query->find('CMS\Entity\MenuItem', $id);
        $menuItemModel = $this->getServiceLocator()->get('CMS\Model\MenuItem');
        $options = array();
        $options['query'] = $query;
        // hide current menu from possible parents options
        $options['hiddenMenuItemsIds'] = array($id);
        $form = new MenuItemForm(/* $name = */ null, $options);
        $form->bind($menuItemObj);
        
        // filling the form with ids not objects 
        $form->get('optgroup-parent')->setValue($menuItemObj->getParent()->getId());
        $form->get('menu')->setValue($menuItemObj->getMenu()->getId());
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($menuItemObj->getInputFilter($query));
            $form->setData($data);
            $menuItemModel->setFormRequiredFields($form, $data);
            if ($form->isValid()) {
                /**
                *  query save function assumes that the name of the field equeal to the name of
                *  the column in database so we adding data with the same name to override this
                *  problem
                */
                $data['parent'] = empty($data['optgroup-parent'])?null:$data['optgroup-parent'];
                $query->setEntity('CMS\Entity\MenuItem')->save($menuItemObj, $data);
                $this->flushMenuCache();

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables = $formSmasher->prepareFormForDisplay($form, /* elements containers */ $variables, array('buttons'));
        return new ViewModel($variables);
    }

    /**
     * Delete menu item
     *
     *
     * @access public
     */
    public function deactivateAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $menuItemObj = $query->find('CMS\Entity\MenuItem', $id);

        $menuItemObj->setStatus(Status::STATUS_INACTIVE);

        $query->save($menuItemObj);
        $this->flushMenuCache();

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
        $this->redirect()->toUrl($url);
    }

    /**
     * activate menu item
     *
     *
     * @access public
     */
    public function activateAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $menuItemObj = $query->find('CMS\Entity\MenuItem', $id);

        $menuItemObj->setStatus(Status::STATUS_ACTIVE);

        $query->save($menuItemObj);
        $this->flushMenuCache();

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'cmsMenuItem'));
        $this->redirect()->toUrl($url);
    }

    /**
     * flush menu cache
     */
    public function flushMenuCache()
    {
        // flush cache of menu items after adding any menu item change
        /* @var $cmsCacheHandler \CMS\Service\Cache\CacheHandler */
        $cmsCacheHandler = $this->getServiceLocator()->get('cmsCacheHandler');
        $cmsCacheHandler->flushCMSCache(\CMS\Service\Cache\CacheHandler::MENUS_KEY);
    }

}
