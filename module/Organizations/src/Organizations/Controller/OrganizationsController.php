<?php

namespace Organizations\Controller;

use Zend\View\Model\ViewModel;
use Utilities\Controller\ActionController;
use Organizations\Form\OrgForm as OrgForm;
use Organizations\Entity\Organization as OrgEntity;
use Organizations\Model\Organization as OrgModel;

/**
 * Atps Controller
 * 
 * Atps entries listing
 * 
 * 
 * 
 * @package organizations
 * @subpackage controller
 */
class OrganizationsController extends ActionController
{

    public function typeAction()
    {
        $variables = array();
        $form = new \Organizations\Form\TypeForm(/* $name = */ null);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                    $request->getPost()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {
                $url = $this->getEvent()->getRouter()->assemble(array('action' => 'new'), array('name' => 'new_org'));
                $this->redirect()->toUrl($url . '?organization=' . $data['type']);
            }
        }

        $variables['TypeForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

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
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $variables['userList'] = $organizationModel->listOrganizations($query, \Organizations\Entity\Organization::TYPE_ATC);

        foreach ($variables['userList'] as $user) {
            $user->atcLicenseExpiration = $user->getAtcLicenseExpiration()->format('d/m/Y');
        }
        return new ViewModel($variables);
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


        $query = $this->getServiceLocator()->get('wrapperQuery');
        $organizationModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $variables['userList'] = $organizationModel->listOrganizations($query, \Organizations\Entity\Organization::TYPE_ATP);

        foreach ($variables['userList'] as $user) {
            $user->atpLicenseExpiration = $user->getAtpLicenseExpiration()->format('d/m/Y');
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
        $variables = $organizationModel->prepareStatics($variables);
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
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Users\Entity\User');
        $orgsQuery = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Organizations\Entity\Organization');
        $orgModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $orgObj = new OrgEntity();
        $options = array();
        $auth = new \Zend\Authentication\AuthenticationService();
        $creatorId = $auth->getIdentity()['id'];
        $options['query'] = $query;
        $options['staticLangs'] = OrgEntity::getStaticLangs();
        $options['staticOss'] = OrgEntity::getOSs();
        $options['staticOfficeVersions'] = OrgEntity::getOfficeVersions();
        $form = new OrgForm(/* $name = */ null, $options);
        $atcSkippedParams = $this->getServiceLocator()->get('Config')['atcSkippedParams'];
        $atpSkippedParams = $this->getServiceLocator()->get('Config')['atpSkippedParams'];
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
            $inputFilter = $orgObj->getInputFilter($query);
            $form->setInputFilter($orgObj->getInputFilter($orgsQuery));
            $form->setData($data);


            switch ($data['type']) {
                case '1':
                    foreach ($atcSkippedParams as $param) {
                        $inputFilter->get($param)->setRequired(false);
                        $data[$param] = null;
                    }
                    break;

                case '2':

                    foreach ($atpSkippedParams as $param) {
                        $inputFilter->get($param)->setRequired(false);
                        $data[$param] = null;
                    }
                    break;
            }
            // not approved
            $data['active'] = OrgEntity::NOT_APPROVED;

            if ($form->isValid()) {

                $orgModel->saveOrganization($data, null, $creatorId);

                // redirecting
                if ($data['type'] == 1) {
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atps'), array('name' => 'list_atc_orgs'));
                }
                else if ($data['type'] == 2 || $data['type'] == 3) {
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atcs'), array('name' => 'list_atp_orgs'));
                }

                $this->redirect()->toUrl($url);
            }
        }

        $variables['orgForm'] = $this->getFormView($form);
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
        $variables = array();
        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $orgsQuery = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Organizations\Entity\Organization');
        $orgObj = $query->find('Organizations\Entity\Organization', $id);
        // for checking on attachments 
        $crAttachment = $orgObj->CRAttachment;
        $atcLicenseAttachment = $orgObj->atcLicenseAttachment;
        $atpLicenseAttachment = $orgObj->atpLicenseAttachment;

        // allow access for admins for all users
        // restrict access for current user only for non-admin users

        $options = array();
        $options['query'] = $query;
        $options['staticLangs'] = OrgEntity::getStaticLangs();
        $options['staticOss'] = OrgEntity::getOSs();
        $options['staticOfficeVersions'] = OrgEntity::getOfficeVersions();
        $atcSkippedParams = $this->getServiceLocator()->get('Config')['atcSkippedParams'];
        $atpSkippedParams = $this->getServiceLocator()->get('Config')['atpSkippedParams'];
        $form = new orgForm(/* $name = */ null, $options);

        $form->bind($orgObj);

        $request = $this->getRequest();
        if ($request->isPost()) {

//            // Make certain to merge the files info!
//            $fileData = $request->getFiles()->toArray();
//
            $fileData = $request->getFiles()->toArray();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );

            $form->setInputFilter($orgObj->getInputFilter($orgsQuery));
            $inputFilter = $form->getInputFilter();
            // edited
            $data['active'] = OrgEntity::EDITED;
            $form->setData($data);
            // file not updated
            if (isset($fileData['CRAttachment']['name']) && empty($fileData['CRAttachment']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('CRAttachment');
                $input->setRequired(false);
            }
            if (isset($fileData['wireTransferAttachment']['name']) && empty($fileData['wireTransferAttachment']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('wireTransferAttachment');
                $input->setRequired(false);
            }
            if (isset($fileData['atcLicenseAttachment']['name']) && empty($fileData['atcLicenseAttachment']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('atcLicenseAttachment');
                $input->setRequired(false);
            }
            if (isset($fileData['atpLicenseAttachment']['name']) && empty($fileData['atpLicenseAttachment']['name'])) {
                // Change required flag to false for any previously uploaded files
                $input = $inputFilter->get('atpLicenseAttachment');
                $input->setRequired(false);
            }

            switch ($data['type']) {
                case '1':
                    foreach ($atcSkippedParams as $param) {
                        $inputFilter->get($param)->setRequired(false);
                        $data[$param] = null;
                    }
                    break;

                case '2':

                    foreach ($atpSkippedParams as $param) {
                        $inputFilter->get($param)->setRequired(false);
                        $data[$param] = null;
                    }
                    break;
            }

            if ($form->isValid()) {
                $orgModel = new OrgModel($query);

                $orgModel->saveOrganization($data, $orgObj);

                // redirecting
                if ($data['type'] == 1) {
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atps'), array('name' => 'list_atc_orgs'));
                }
                else if ($data['type'] == 2 || $data['type'] == 3) {
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atcs'), array('name' => 'list_atp_orgs'));
                }
                $this->redirect()->toUrl($url);
            }
        }
        $variables['wireTransferAttachment'] = $crAttachment;
        $variables['CRAttachment'] = $crAttachment;
        $variables['atpLicenseAttachment'] = $atpLicenseAttachment;
        $variables['atcLicenseAttachment'] = $atcLicenseAttachment;
        $variables['userForm'] = $this->getFormView($form);
        return new ViewModel($variables);
    }

    public function deleteAction()
    {

        $id = $this->params('id');
        $query = $this->getServiceLocator()->get('wrapperQuery');
//        $orgsQuery = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Organizations\Entity\Org');
        $orgObj = $query->find('Organizations\Entity\Organization', $id);

        $orgModel = $this->getServiceLocator()->get('Organizations\Model\Organization');
        $orgModel->deleteOrganization($id);



        if ($orgObj->type == 1) {
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atps'), array('name' => 'list_atc_orgs'));
        }
        else if ($orgObj->type == 2 || $data['type'] == 3) {
            $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atcs'), array('name' => 'list_atp_orgs'));
        }
        $this->redirect()->toUrl($url);
    }

}
