<?php

namespace Organizations\Controller;

use Zend\View\Model\ViewModel;
use Utilities\Controller\ActionController;
use \Zend\InputFilter\InputFilterInterface;

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

            $messages = $this->validateOrg($data);

            if (empty($messages)) {

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
            else {
                $variables['messages'] = $messages;
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

    function validateOrg($data)
    {
        $messages = array();
        if ($data['type'] == 1) {
            $messages = $this->validateAtcData($messages, $data);
        }
        else if ($data['type'] == 2) {
            $messages = $this->validateAtpData($messages, $data);
        }
        else {
            $messages = $this->validateAtcData($messages, $data);
            $messages = $this->validateAtpData($messages, $data);
        }

        return ($messages);
    }

    function validateAtpData($messages, $data)
    {
        if ($data['atpLicenseNo'] == "" || $data['atpLicenseNo'] == null) {
            array_push($messages, "please insert proper ATP License No");
        }
        if ($data['atpLicenseExpiration'] == "" || $data['atpLicenseExpiration'] == null) {
            array_push($messages, "please insert proper ATP Expiration Date");
        }
        if ($data['atpLicenseAttachment'] == "" || $data['atpLicenseAttachment'] == null) {
            array_push($messages, "please insert proper ATP License (pdf , jpg ,jpeg) only");
        }
        if ($data['labsNo'] == "" || $data['labsNo'] == null) {
            array_push($messages, "please insert proper labs number");
        }
        if ($data['pcsNo_lab'] == "" || $data['pcsNo_lab'] == null) {
            array_push($messages, "please insert proper pcs per lab");
        }

        return $messages;
    }

    function validateAtcData($messages, $data)
    {
        if ($data['atcLicenseNo'] == "" || $data['atcLicenseNo'] == null) {
            array_push($messages, "please insert proper ATC License No");
        }
        if ($data['atcLicenseExpiration'] == "" || $data['atcLicenseExpiration'] == null) {
            array_push($messages, "please insert proper ATC Expiration Date");
        }
        if ($data['atcLicenseAttachment'] == "" || $data['atcLicenseAttachment'] == null) {
            array_push($messages, "please insert proper ATC License (pdf , jpg ,jpeg) only");
        }
        if ($data['classesNo'] == "" || $data['classesNo'] == null) {
            array_push($messages, "please insert proper classes number");
        }
        if ($data['pcsNo_class'] == "" || $data['pcsNo_class'] == null) {
            array_push($messages, "please insert proper pcs per class");
        }
        return $messages;
    }

}
