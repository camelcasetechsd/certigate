<?php

namespace Organizations\Controller;

use Zend\View\Model\ViewModel;
use Utilities\Controller\ActionController;

/**
 * Atps Controller
 * 
 * Atps entries listing
 * 
 * 
 * 
 * @package directories
 * @subpackage controller
 */
class OrganizationsController extends ActionController
{

    /**
     * List ATCs
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function atcsAction()
    {
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $variables['userList'] = $organizationModel->getOrganizationBy('type', array(\Organizations\Entity\Organization::TYPE_ATC, \Organizations\Entity\Organization::TYPE_BOTH));

        foreach ($variables['userList'] as $user) {
            $user->atcLicenseExpiration = $user->getAtcLicenseExpiration()->format('Y-m-d');
        }
        
        return new ViewModel($variables);
//        return new ViewModel();
    }

    /**
     * List ATCs
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function atpsAction()
    {
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $variables['userList'] = $organizationModel->getOrganizationBy('type', array(\Organizations\Entity\Organization::TYPE_ATP, \Organizations\Entity\Organization::TYPE_BOTH));

        foreach ($variables['userList'] as $user) {
            $user->atpLicenseExpiration = $user->getAtpLicenseExpiration()->format('Y-m-d');
        }
        return new ViewModel($variables);
    }

    /**
     * more details of an ATC
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function moreAction()
    {
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $variables['userData'] = $organizationModel->getOrganizationby('id', $id)[0];


        $variables['userData']->CRExpiration = $variables['userData']->getCRExpiration()->format('Y-m-d');
        // skip atc expiration if atp
        if ($variables['userData']->atcLicenseExpiration != null) {
            $variables['userData']->atcLicenseExpiration = $variables['userData']->getAtcLicenseExpiration()->format('Y-m-d');
        }
        // skip atp expiration if atc
        if ($variables['userData']->atpLicenseExpiration != null) {
            $variables['userData']->atpLicenseExpiration = $variables['userData']->getAtpLicenseExpiration()->format('Y-m-d');
        }

        return new ViewModel($variables);
    }

    /**
     * create new ATC
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        // getting users to be displayed in dropdown menus 
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $data = $organizationModel->getUsers();
        $objectUtilities = $this->getServiceLocator()->get('objectUtilities');
        // users object
        $variables['userList'] = $objectUtilities->prepareForDisplay($data);

        $request = $this->getRequest();
        if ($request->isPost()) {

            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();

            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );

            $organizationModel->saveOrganization($data);
            if ($data['type'] == \Organizations\Entity\Organization::TYPE_ATC) {
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atcs'), array('name' => 'list_atcs'));
                $this->redirect()->toUrl($url);
            }
            else {
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atcs'), array('name' => 'list_atps'));
                $this->redirect()->toUrl($url);
            }
        }
        return new ViewModel($variables);
    }

    /**
     * more details of an ATC
     * 
     * 
     * @access public
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        // getting users to be displayed in dropdown menus 
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');

        $variables['userData'] = $organizationModel->getOrganizationby('id', $id)[0];
        $variables['userData']->CRExpiration = $variables['userData']->getCRExpiration()->format('d/m/Y');
        $variables['userData']->atcLicenseExpiration = $variables['userData']->getAtcLicenseExpiration()->format('d/m/Y');
        $variables['userData']->atpLicenseExpiration = $variables['userData']->getAtpLicenseExpiration()->format('d/m/Y');

        $variables['userList'] = $organizationModel->getUsers();

        $request = $this->getRequest();
        if ($request->isPost()) {


            $data = array_merge_recursive(
                    $this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray()
            );



            $organizationModel->saveOrganization($data, $organizationModel->getOrganizationby('id', $id)[0]);
        }

        return new ViewModel($variables);
    }

}
