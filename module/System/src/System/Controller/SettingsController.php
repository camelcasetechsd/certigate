<?php

namespace System\Controller;

use Utilities\Controller\ActionController;
use Zend\View\Model\ViewModel;
use System\Form\SettingForm;
use System\Entity\Setting;

/**
 * Settings Controller
 * 
 * Settings entries listing
 * 
 * 
 * 
 * @package system
 * @subpackage controller
 */
class SettingsController extends ActionController
{

    /**
     * List Settings
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('System\Entity\Setting');
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');

        $data = $query->findAll(/* $entityName = */null);
        $variables['settings'] = $objectUtilities->prepareForDisplay($data);
        return new ViewModel($variables);
    }

    /**
     * Create new Setting
     * 
     * 
     * @access public
     * @uses Setting
     * @uses SettingForm
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('System\Entity\Setting');
        $formSmasher = $this->getServiceLocator()->get('formSmasher');

        $setting = new Setting();

        $form = new SettingForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($setting->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($setting, $data);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'systemSettings'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables = $formSmasher->prepareFormForDisplay($form, $variables);
        return new ViewModel($variables);
    }

    /**
     * Edit Setting
     * 
     * 
     * @access public
     * @uses SettingForm
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $formSmasher = $this->getServiceLocator()->get('formSmasher');

        $setting = $query->find('System\Entity\Setting', $id);

        $form = new SettingForm();
        $form->bind($setting);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setInputFilter($setting->getInputFilter($query));
            $form->setData($data);
            if ($form->isValid()) {
                $query->save($setting);

                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'systemSettings'));
                $this->redirect()->toUrl($url);
            }
        }

        $variables = $formSmasher->prepareFormForDisplay($form, $variables);
        return new ViewModel($variables);
    }

    /**
     * Delete Setting
     *
     * 
     * @access public
     */
    public function deleteAction()
    {
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $setting = $query->find('System\Entity\Setting', $id);

        $query->remove($setting);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index'), array('name' => 'systemSettings'));
        $this->redirect()->toUrl($url);
    }
    
    /**
     * flush settings cache
     */
    public function flushMenuCache()
    {
        // flush cache of menu items after adding any settings change
        /* @var $systemCacheHandler \System\Service\Cache\CacheHandler */
        $systemCacheHandler = $this->getServiceLocator()->get('systemCacheHandler');
        $systemCacheHandler->flushCMSCache(\System\Service\Cache\CacheHandler::SETTINGS_KEY);
    }

}
