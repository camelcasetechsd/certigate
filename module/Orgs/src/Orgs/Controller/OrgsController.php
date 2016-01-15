<?php

namespace Orgs\Controller;

use Zend\View\Model\ViewModel;
use Utilities\Controller\ActionController;
use Orgs\Form\OrgForm;
use Orgs\Entity\Org as OrgEntity;
use Orgs\Model\Org as OrgModel;

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
class OrgsController extends ActionController
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

        $organizationModel = $this->getServiceLocator()->get('Orgs\Model\Org');
        $variables['userList'] = $organizationModel->getOrganizationBy('type', array(\Orgs\Entity\Org::TYPE_ATC, \Orgs\Entity\Org::TYPE_BOTH));

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


        $organizationModel = $this->getServiceLocator()->get('Orgs\Model\Org');
        $variables['userList'] = $organizationModel->getOrganizationBy('type', array(\Orgs\Entity\Org::TYPE_ATP, \Orgas\Entity\Org::TYPE_BOTH));

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

        $organizationModel = $this->getServiceLocator()->get('Orgs\Model\Org');
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
        $variables = array();
        $query = $this->getServiceLocator()->get('wrapperQuery')->setEntity('Users\Entity\User');
        $orgModel = $this->getServiceLocator()->get('Orgs\Model\Org');
        $orgObj = new OrgEntity();
        $options = array();
        $options['query'] = $query;

        $form = new OrgForm(/* $name = */ null, $options);

        $request = $this->getRequest();
        if ($request->isPost()) {

            // Make certain to merge the files info!
            $fileData = $request->getFiles()->toArray();
//
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $fileData
            );
//
            $form->setInputFilter($orgObj->getInputFilter());
            $form->setData($data);

            if (!empty($orgModel->checkOrgExistance($data['commercialName']))) {
                $form->get('commercialName')->setMessages(array("Organization already Exists"));
            }






            /*
             * Add inputs validations here
             */

            if ($form->isValid()) {

                $orgModel->saveOrganization($data);






                // redirecting

                if ($data['type'] == 1) {
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atps'), array('name' => 'list_atc_orgs'));
                }
                else if ($data['type'] == 2) {
                    $url = $this->getEvent()->getRouter()->assemble(array('action' => 'atcs'), array('name' => 'list_atp_orgs'));
                }

                $this->redirect()->toUrl($url);
            }
            else {

                var_dump("invalid");
                exit;
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

        return new ViewModel();
    }

}
